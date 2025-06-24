<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::with(['customer', 'items'])
            ->latest()
            ->paginate(15);

        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        $products = Product::active()->get();
        $customers = Customer::orderBy('name')->get();
        
        return view('sales.create', compact('products', 'customers'));
    }

    public function store(Request $request)
    {
        // Debug the incoming request
        \Log::info('Sale Request Data:', [
            'all' => $request->all(),
            'items' => $request->input('items'),
            'hasItems' => $request->has('items')
        ]);

        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'payment_status' => 'required|in:paid,pending,partial',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            // Generate a unique invoice number
            $invoiceNumber = 'INV-' . date('Ymd') . '-' . strtoupper(uniqid());
            
            $sale = Sale::create([
                'customer_id' => $validated['customer_id'],
                'user_id' => auth()->id(),
                'invoice_number' => $invoiceNumber,
                'subtotal' => 0,
                'tax_amount' => 0, // You might want to calculate this based on your tax logic
                'discount_amount' => 0,
                'total' => 0,
                'amount_paid' => 0, // This should be set based on payment status
                'payment_method' => 'cash', // Default payment method, adjust as needed
                'notes' => $validated['notes'] ?? null,
            ]);

            $totalAmount = 0;

            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                
                if (!$this->checkInventory($product, $item['quantity'])) {
                    throw new \Exception("Insufficient inventory for {$product->name}");
                }

                $subtotal = $product->price * $item['quantity'];
                $totalAmount += $subtotal;

                $sale->items()->attach($product->id, [
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->price,
                    'subtotal' => $subtotal,
                ]);

                $this->updateInventory($product, $item['quantity'], 'sale');
            }

            // Update the sale with calculated amounts
            $sale->update([
                'subtotal' => $totalAmount,
                'total' => $totalAmount + $sale->tax_amount - $sale->discount_amount,
                'amount_paid' => $validated['payment_status'] === 'paid' ? $totalAmount : 0,
            ]);
            DB::commit();

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Sale completed successfully',
                    'redirect' => route('sales.show', $sale)
                ]);
            }

            return redirect()->route('sales.show', $sale)
                ->with('success', 'Sale completed successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            $errorMessage = 'Error processing sale: ' . $e->getMessage();
            Log::error($errorMessage);
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ], 422);
            }
            
            return back()->with('error', $errorMessage);
        }
    }

    public function show(Sale $sale)
    {
        $sale->load(['customer', 'items', 'user']);
        return view('sales.show', compact('sale'));
    }

    private function checkInventory(Product $product, $quantity)
    {
        foreach ($product->ingredients as $ingredient) {
            $required = $ingredient->pivot->quantity * $quantity;
            if ($ingredient->current_stock < $required) {
                return false;
            }
        }
        return true;
    }

    private function updateInventory(Product $product, $quantity, $action = 'sale')
    {
        $multiplier = ($action === 'sale') ? -1 : 1;
        
        foreach ($product->ingredients as $ingredient) {
            $amount = $ingredient->pivot->quantity * $quantity * $multiplier;
            $ingredient->increment('current_stock', $amount);
            
            // Log the inventory change
            $ingredient->inventoryLogs()->create([
                'quantity' => $amount,
                'type' => $action === 'sale' ? 'sale' : 'adjustment',
                'notes' => $action === 'sale' 
                    ? 'Deducted for sale' 
                    : 'Inventory adjustment',
                'user_id' => auth()->id(),
            ]);
        }
    }
}
