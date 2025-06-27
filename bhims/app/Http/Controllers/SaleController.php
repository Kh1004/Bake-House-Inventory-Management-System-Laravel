<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Mail\SaleReceiptMail;

class SaleController extends Controller
{
    /**
     * Display a listing of the sales.
     */
    public function index()
    {
        $sales = Sale::with(['customer', 'items.product'])
            ->latest()
            ->paginate(15);

        return view('sales.index', compact('sales'));
    }

    /**
     * Show the form for creating a new sale.
     */
    public function create()
    {
        $products = Product::active()->get();
        $customers = Customer::orderBy('name')->get();
        
        return view('sales.create', compact('products', 'customers'));
    }

    /**
     * Store a newly created sale in storage.
     */
    public function store(Request $request)
    {
        \Log::info('Sale Store Request:', $request->all());
        
        try {
            // Validate the request
            $validated = $request->validate([
                'customer_id' => 'required|exists:customers,id',
                'payment_status' => 'required|in:paid,pending,partial',
                'payment_method' => 'required_if:payment_status,paid,partial|string|nullable',
                'notes' => 'nullable|string',
                'items' => 'required|array|min:1',
                'items.*.product_id' => 'required|exists:products,id',
                'items.*.quantity' => 'required|integer|min:1',
                'items.*.unit_price' => 'required|numeric|min:0',
                'items.*.total' => 'required|numeric|min:0',
            ]);
            
            \Log::info('Validation passed', $validated);

            // Start database transaction
            DB::beginTransaction();

            try {
                // Generate a unique invoice number (e.g., INV-YYYYMMDD-XXXXX)
                $latestSale = Sale::latest()->first();
                $invoiceNumber = 'INV-' . now()->format('Ymd') . '-' . str_pad(($latestSale ? $latestSale->id + 1 : 1), 5, '0', STR_PAD_LEFT);
                
                // Calculate amounts
                $subtotal = collect($validated['items'])->sum('total');
                $taxAmount = 0; // Default tax amount (can be calculated based on your requirements)
                $discountAmount = 0; // Default discount amount
                $totalAmount = $subtotal + $taxAmount - $discountAmount;
                $amountPaid = $validated['payment_status'] === 'paid' ? $totalAmount : 0; // If paid, amount_paid = total, else 0
                $changeAmount = 0; // Default change amount
                
                // Create the sale
                $sale = Sale::create([
                    'customer_id' => $validated['customer_id'],
                    'user_id' => auth()->id(),
                    'invoice_number' => $invoiceNumber,
                    'subtotal' => $subtotal,
                    'tax_amount' => $taxAmount,
                    'discount_amount' => $discountAmount,
                    'total' => $totalAmount,
                    'amount_paid' => $amountPaid,
                    'change_amount' => $changeAmount,
                    'payment_method' => $validated['payment_method'] ?? null,
                    'payment_status' => $validated['payment_status'],
                    'notes' => $validated['notes'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                \Log::info('Sale created', ['sale_id' => $sale->id]);

                

                // Add sale items
                foreach ($validated['items'] as $index => $item) {
                    \Log::debug('Processing item ' . ($index + 1), $item);
                    
                    $saleItem = $sale->items()->create([
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                        'total_price' => $item['total'],
                    ]);
                    
                    \Log::debug('Sale item created', ['sale_item_id' => $saleItem->id]);

                    // Update product stock
                    $product = Product::find($item['product_id']);
                    if ($product) {
                        $oldStock = $product->current_stock;
                        $product->decrement('current_stock', $item['quantity']);
                        $product->save();
                        \Log::debug('Product stock updated', [
                            'product_id' => $product->id,
                            'old_stock' => $oldStock,
                            'new_stock' => $product->current_stock,
                            'quantity_sold' => $item['quantity']
                        ]);
                    } else {
                        throw new \Exception("Product not found: " . $item['product_id']);
                    }
                }

                DB::commit();
                \Log::info('Sale completed successfully', ['sale_id' => $sale->id]);

                return response()->json([
                    'success' => true,
                    'message' => 'Sale created successfully',
                    'redirect' => route('sales.show', $sale->id),
                    'sale_id' => $sale->id
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                \Log::error('Error during sale processing: ' . $e->getMessage(), [
                    'exception' => $e,
                    'trace' => $e->getTraceAsString()
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Error processing sale: ' . $e->getMessage(),
                    'error_details' => config('app.debug') ? [
                        'message' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'trace' => $e->getTraceAsString()
                    ] : null
                ], 500);
            }
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error: ' . $e->getMessage(), [
                'errors' => $e->errors()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            \Log::error('Unexpected error: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred: ' . $e->getMessage(),
                'error_details' => config('app.debug') ? [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ] : null
            ], 500);
        }
    }

    /**
     * Display the specified sale.
     */
    public function show(Sale $sale)
    {
        $sale->load(['customer', 'items.product']);
        
        return view('sales.show', compact('sale'));
    }

    /**
     * Show the form for editing the specified sale.
     */
    public function edit(Sale $sale)
    {
        $sale->load('items.product');
        $products = Product::active()->get();
        $customers = Customer::orderBy('name')->get();
        
        return view('sales.edit', compact('sale', 'products', 'customers'));
    }

    /**
     * Update the specified sale in storage.
     */
    public function update(Request $request, Sale $sale)
    {
        \Log::info('Update request data:', $request->all());
        // Validate the request
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'payment_status' => 'required|in:paid,pending,partial',
            'payment_method' => 'required_if:payment_status,paid,partial|string|nullable',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.total' => 'required|numeric|min:0',
        ]);

        // Start database transaction
        DB::beginTransaction();

        try {
            // First, return all items to stock
            foreach ($sale->items as $item) {
                $product = $item->product;
                if ($product) {
                    $product->increment('current_stock', $item->quantity);
                }
            }


            // Delete all existing items
            $sale->items()->delete();


            // Calculate amounts
            $subtotal = collect($validated['items'])->sum('total');
            $taxAmount = 0; // Default tax amount (can be calculated based on your requirements)
            $discountAmount = 0; // Default discount amount
            $totalAmount = $subtotal + $taxAmount - $discountAmount;
            $amountPaid = $validated['payment_status'] === 'paid' ? $totalAmount : 0; // If paid, amount_paid = total, else 0
            $changeAmount = 0; // Default change amount

            // Update the sale
            $sale->update([
                'customer_id' => $validated['customer_id'],
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'discount_amount' => $discountAmount,
                'total' => $totalAmount,
                'amount_paid' => $amountPaid,
                'change_amount' => $changeAmount,
                'payment_status' => $validated['payment_status'],
                'payment_method' => $validated['payment_method'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);

            // Add new sale items
            foreach ($validated['items'] as $item) {
                $sale->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['quantity'] * $item['unit_price'],
                ]);

                // Update product stock
                $product = Product::find($item['product_id']);
                if ($product) {
                    $product->decrement('current_stock', $item['quantity']);
                }
            }


            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Sale updated successfully',
                'redirect' => route('sales.show', $sale->id)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating sale: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Error updating sale: ' . $e->getMessage(),
                'error_details' => config('app.debug') ? [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ] : null
            ], 500);
        }
    }

    /**
     * Remove the specified sale from storage.
     */
    public function destroy(Sale $sale)
    {
        DB::beginTransaction();

        try {
            // Return all items to stock
            foreach ($sale->items as $item) {
                $product = $item->product;
                if ($product) {
                    $product->increment('stock_quantity', $item->quantity);
                }
            }


            // Delete the sale and its items
            $sale->items()->delete();
            $sale->delete();

            DB::commit();

            return redirect()->route('sales.index')
                ->with('success', 'Sale deleted successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting sale: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Error deleting sale. Please try again.');
        }
    }

    /**
     * Get product details for sale form
     */
    public function getProduct($id)
    {
        $product = Product::findOrFail($id);
        
        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'stock_quantity' => $product->stock_quantity,
            'unit' => $product->unit ?? 'pcs',
            'description' => $product->description
        ]);
    }

    /**
     * Send a receipt for the specified sale via email.
     *
     * @param  \App\Models\Sale  $sale
     * @return \Illuminate\Http\RedirectResponse
     */
    /**
     * Display the printable version of the sale invoice.
     *
     * @param  \App\Models\Sale  $sale
     * @return \Illuminate\View\View
     */
    public function print(Sale $sale)
    {
        // Load necessary relationships
        $sale->load(['customer', 'items.product', 'user']);
        
        // Return the print view
        return view('sales.print', compact('sale'));
    }

    /**
     * Send a receipt for the specified sale via email.
     *
     * @param  \App\Models\Sale  $sale
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendReceipt(Sale $sale)
    {
        try {
            // Load the necessary relationships
            $sale->load(['customer', 'items.product', 'user']);
            
            // Check if customer has an email
            if (empty($sale->customer->email)) {
                return back()->with('error', 'Customer does not have an email address.');
            }
            
            // Send the email
            Mail::to($sale->customer->email)
                ->send(new SaleReceiptMail($sale));
                
            return back()->with('success', 'Receipt has been sent to ' . $sale->customer->email);
            
        } catch (\Exception $e) {
            Log::error('Error sending sale receipt email: ' . $e->getMessage());
            return back()->with('error', 'Failed to send email. Please try again.');
        }
    }
}
