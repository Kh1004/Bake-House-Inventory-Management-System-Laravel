<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleProduct;
use App\Models\Customer;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SalesDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();
        $customers = Customer::all();
        $users = User::all();
        
        if ($products->isEmpty() || $customers->isEmpty() || $users->isEmpty()) {
            $this->command->info('No products, customers, or users found. Skipping sales data seeding.');
            return;
        }

        // Generate sales data for the last 90 days
        for ($daysAgo = 89; $daysAgo >= 0; $daysAgo--) {
            $saleDate = Carbon::now()->subDays($daysAgo);
            
            // Generate 1-5 sales per day
            $salesCount = rand(1, 5);
            
            for ($i = 0; $i < $salesCount; $i++) {
                $customer = $customers->random();
                $saleTime = $saleDate->copy()->addHours(rand(9, 20))->addMinutes(rand(0, 59));
                
                // Generate a unique invoice number
                $invoiceNumber = 'INV-' . $saleTime->format('Ymd') . '-' . strtoupper(uniqid());
                
                $sale = Sale::create([
                    'customer_id' => $customer->id,
                    'user_id' => $users->random()->id, // Random user
                    'invoice_number' => $invoiceNumber,
                    'subtotal' => 0, // Will be updated after adding products
                    'tax_amount' => 0, // No tax for now
                    'discount_amount' => 0, // No discount
                    'total' => 0, // Will be updated after adding products
                    'amount_paid' => 0, // Will be updated after adding products
                    'change_amount' => 0, // No change
                    'payment_method' => ['cash', 'card', 'online'][array_rand(['cash', 'card', 'online'])],
                    'payment_reference' => rand(100000, 999999),
                    'notes' => null,
                    'created_at' => $saleTime,
                    'updated_at' => $saleTime,
                ]);
                
                // Add 1-5 products to each sale
                $productCount = rand(1, min(5, $products->count()));
                $saleProducts = $products->random($productCount);
                $totalAmount = 0;
                
                foreach ($saleProducts as $product) {
                    $quantity = rand(1, 5);
                    $unitPrice = $product->selling_price;
                    $subtotal = $quantity * $unitPrice;
                    
                    // Create sale product
                    SaleProduct::create([
                        'sale_id' => $sale->id,
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'unit_price' => $unitPrice,
                        'total_price' => $subtotal,
                        'created_at' => $saleTime,
                        'updated_at' => $saleTime,
                    ]);
                    
                    $totalAmount += $subtotal;
                    
                    // Create daily sales record for demand prediction
                    $this->createDailySaleRecord($product, $saleTime, $quantity);
                }
                
                // Calculate tax (10% of subtotal for example)
                $taxAmount = $totalAmount * 0.1;
                $grandTotal = $totalAmount + $taxAmount;
                
                // Update sale amounts
                $sale->update([
                    'subtotal' => $totalAmount,
                    'tax_amount' => $taxAmount,
                    'total' => $grandTotal,
                    'amount_paid' => $grandTotal, // Assuming full payment
                ]);
            }
        }
        
        $this->command->info('Successfully seeded sales data for demand prediction.');
    }
    
    /**
     * Create or update daily sale record for a product
     */
    private function createDailySaleRecord($product, $saleTime, $quantity): void
    {
        $date = $saleTime->format('Y-m-d');
        
        // Check if record exists for this product and date
        $dailySale = \App\Models\DailySale::firstOrNew([
            'product_id' => $product->id,
            'date' => $date,
        ]);
        
        $dailySale->quantity_sold = ($dailySale->quantity_sold ?? 0) + $quantity;
        $dailySale->revenue = ($dailySale->revenue ?? 0) + ($quantity * $product->price);
        $dailySale->save();
    }
}