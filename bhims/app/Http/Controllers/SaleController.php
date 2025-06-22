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
            $sale = Sale::create([
                'customer_id' => $validated['customer_id'],
                'total_amount' => 0,
                'payment_status' => $validated['payment_status'],
                'sale_date' => now(),
                'notes' => $validated['notes'] ?? null,
                'user_id' => auth()->id(),
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

            $sale->update(['total_amount' => $totalAmount]);
            DB::commit();

            return redirect()->route('sales.show', $sale)
                ->with('success', 'Sale completed successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Sale Error: ' . $e->getMessage());
            return back()->with('error', 'Error processing sale: ' . $e->getMessage());
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
