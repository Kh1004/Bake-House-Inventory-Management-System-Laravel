<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Supplier;
use App\Models\Ingredient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of the purchase orders.
     */
    public function index()
    {
        $purchaseOrders = PurchaseOrder::with([
                'supplier:id,name,email',
                'user:id,name',
                'items:ingredient_id,purchase_order_id' // Only select necessary columns
            ])
            ->withCount('items') // Add item count to avoid N+1 queries
            ->latest()
            ->paginate(15);

        return view('purchase-orders.index', compact('purchaseOrders'));
    }

    /**
     * Show the form for creating a new purchase order.
     */
    public function create(Request $request)
    {
        $suppliers = Supplier::orderBy('name')->get();
        $ingredients = Ingredient::orderBy('name')->get();
        
        $lowStockItems = [];
        
        // Check if we should pre-select low stock items
        if ($request->has('low_stock_items')) {
            $lowStockItems = Ingredient::whereColumn('current_stock', '<=', 'minimum_stock')
                ->where('minimum_stock', '>', 0)
                ->orderBy('name')
                ->get()
                ->map(function($ingredient) {
                    return [
                        'id' => $ingredient->id,
                        'name' => $ingredient->name,
                        'unit_of_measure' => $ingredient->unit_of_measure,
                        'current_stock' => $ingredient->current_stock,
                        'minimum_stock' => $ingredient->minimum_stock,
                        'suggested_quantity' => max(1, ceil(($ingredient->minimum_stock * 1.5) - $ingredient->current_stock)),
                        'unit_price' => $ingredient->unit_price,
                        'supplier_id' => $ingredient->supplier_id
                    ];
                })
                ->toArray();
        }
        
        return view('purchase-orders.create', compact('suppliers', 'ingredients', 'lowStockItems'));
    }

    /**
     * Store a newly created purchase order in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'expected_delivery_date' => 'required|date|after_or_equal:today',
            'notes' => 'nullable|string|max:1000',
            'total_amount' => 'required|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.ingredient_id' => 'required|exists:ingredients,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
        ], [
            'items.required' => 'Please add at least one item to the purchase order.',
            'items.*.ingredient_id.required' => 'Please select an ingredient for all items.',
            'items.*.quantity.min' => 'Quantity must be greater than 0.',
            'items.*.unit_price.min' => 'Unit price cannot be negative.',
        ]);

        DB::beginTransaction();

        try {
            // Generate PO number with date and random string
            $poNumber = 'PO-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));
            
            // Calculate total amount from items (as a secondary validation)
            $calculatedTotal = collect($validated['items'])->sum(function($item) {
                return $item['quantity'] * $item['unit_price'];
            });
            
            // Verify calculated total matches the submitted total (prevent tampering)
            if (abs($calculatedTotal - $validated['total_amount']) > 0.01) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['total_amount' => 'The calculated total does not match the submitted total.']);
            }

            // Create purchase order with the validated total amount
            $purchaseOrder = PurchaseOrder::create([
                'po_number' => $poNumber,
                'supplier_id' => $validated['supplier_id'],
                'user_id' => Auth::id(),
                'order_date' => now(),
                'expected_delivery_date' => $validated['expected_delivery_date'],
                'status' => 'draft',
                'notes' => $validated['notes'] ?? null,
                'total_amount' => $validated['total_amount'],
            ]);

            // Add items to purchase order with validation
            $ingredientIds = [];
            $orderItems = [];
            $now = now();
            
            foreach ($validated['items'] as $item) {
                // Check for duplicate ingredients
                if (in_array($item['ingredient_id'], $ingredientIds)) {
                    DB::rollBack();
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['items' => 'Duplicate ingredients are not allowed.']);
                }
                
                $ingredientIds[] = $item['ingredient_id'];
                
                // Create order item data
                $orderItems[] = [
                    'purchase_order_id' => $purchaseOrder->id, // Add the purchase order ID
                    'ingredient_id' => $item['ingredient_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['quantity'] * $item['unit_price'],
                    'status' => 'pending', // Add default status
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            
            // Insert all items at once for better performance
            $purchaseOrder->items()->insert($orderItems);
            
            // Update purchase order with item count
            $purchaseOrder->update([
                'item_count' => count($orderItems)
            ]);
            
            // Log the purchase order creation
            activity()
                ->causedBy(auth()->user())
                ->performedOn($purchaseOrder)
                ->withProperties(['items_count' => count($orderItems)])
                ->log('created purchase order');

            DB::commit();

            return redirect()->route('purchase-orders.show', $purchaseOrder)
                ->with('success', 'Purchase order #' . $purchaseOrder->po_number . ' has been created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating purchase order: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'An error occurred while creating the purchase order: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified purchase order.
     */
    public function show(PurchaseOrder $purchaseOrder)
    {
        // Eager load relationships with only necessary columns
        $purchaseOrder->load([
            'supplier:id,name,email,phone,address',
            'user:id,name,email',
            'items' => function($query) {
                $query->select([
                    'id',
                    'purchase_order_id',
                    'ingredient_id',
                    'quantity',
                    'unit_price',
                    'total_price',
                    'received_quantity',
                    'status'
                ])->with([
                    'ingredient:id,name,unit_of_measure,current_stock,minimum_stock'
                ]);
            }
        ]);

        // Calculate summary statistics
        $itemCount = $purchaseOrder->items->count();
        $totalItems = $purchaseOrder->items->sum('quantity');
        $receivedItems = $purchaseOrder->items->sum('received_quantity');
        $completionPercentage = $totalItems > 0 
            ? min(100, round(($receivedItems / $totalItems) * 100)) 
            : 0;

        return view('purchase-orders.show', [
            'purchaseOrder' => $purchaseOrder,
            'itemCount' => $itemCount,
            'totalItems' => $totalItems,
            'receivedItems' => $receivedItems,
            'completionPercentage' => $completionPercentage,
        ]);
    }

                    
    /**
     * Update the status of the specified purchase order.
     */
    public function updateStatus(Request $request, PurchaseOrder $purchaseOrder)
    {
        // Validate the request
        $validated = $request->validate([
            'status' => 'required|in:draft,ordered,received,cancelled',
            'notes' => 'nullable|string',
            'items' => 'sometimes|required|array',
            'items.*.ingredient_id' => 'required|exists:ingredients,id',
            'items.*.received_quantity' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $status = $validated['status'];
            $items = $request->input('items', []);
            $totalReceived = 0;
            $totalOrdered = $purchaseOrder->items->sum('quantity');

            // If status is received, validate received quantities
            if ($status === 'received') {
                if (empty($items)) {
                    return redirect()->back()
                        ->with('error', 'Please enter received quantities for at least one item.')
                        ->withInput();
                }

                // Update received quantities and calculate total received
                foreach ($items as $itemData) {
                    $item = $purchaseOrder->items->firstWhere('ingredient_id', $itemData['ingredient_id']);
                    if ($item) {
                        $receivedQty = (float) $itemData['received_quantity'];
                        $maxQty = $item->quantity - ($item->received_quantity ?? 0);
                        
                        if ($receivedQty < 0 || $receivedQty > $maxQty) {
                            throw new \Exception("Invalid received quantity for {$item->ingredient->name}");
                        }
                        
                        $newReceivedQty = ($item->received_quantity ?? 0) + $receivedQty;
                        // Only update received quantity, status is managed at the purchase order level
                        $item->update([
                            'received_quantity' => $newReceivedQty
                        ]);
                        
                        $totalReceived += $receivedQty;
                        
                        // Update inventory
                        $item->ingredient->increment('current_stock', $receivedQty);
                        
                        // Record stock movement
                        $item->ingredient->stockMovements()->create([
                            'quantity' => $receivedQty,
                            'movement_type' => 'purchase',
                            'notes' => 'Received from PO #' . $purchaseOrder->po_number,
                            'user_id' => auth()->id(),
                            'reference_type' => get_class($purchaseOrder),
                            'reference_id' => $purchaseOrder->id,
                        ]);
                    }
                }
                
                // If we've received some items but not all, set status to 'ordered'
                if ($totalReceived > 0 && $totalReceived < $totalOrdered) {
                    $status = 'ordered';
                }
            } else if ($status === 'cancelled') {
                // Mark all items as cancelled
                $purchaseOrder->items()->update([
                    'status' => 'cancelled',
                    'received_quantity' => 0
                ]);
            }

            // Update the purchase order status
            $purchaseOrder->update([
                'status' => $status,
                'notes' => $validated['notes'] ?? $purchaseOrder->notes,
                'received_at' => $status === 'received' ? now() : null
            ]);

            // Log the status update
            activity()
                ->causedBy(auth()->user())
                ->performedOn($purchaseOrder)
                ->withProperties([
                    'status' => $status,
                    'received_quantity' => $totalReceived,
                    'total_quantity' => $totalOrdered
                ])
                ->log('updated purchase order status');

            DB::commit();

            return redirect()->route('purchase-orders.show', $purchaseOrder)
                ->with('success', 'Purchase order status updated successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error updating purchase order status: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());

            return redirect()->back()
                ->withInput()
                ->with('error', 'An error occurred while updating the purchase order status: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified purchase order from storage.
     */
    public function destroy(PurchaseOrder $purchaseOrder)
    {
        // Only allow deletion of pending orders
        if ($purchaseOrder->status !== 'pending') {
            return redirect()->route('purchase-orders.index')
                ->with('error', 'Only pending purchase orders can be deleted');
        }

        DB::beginTransaction();

        try {
            // Delete associated items
            $purchaseOrder->items()->delete();
            
            // Delete the purchase order
            $purchaseOrder->delete();

            DB::commit();

            return redirect()->route('purchase-orders.index')
                ->with('success', 'Purchase order deleted successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error deleting purchase order: ' . $e->getMessage());
        }
    }
}
