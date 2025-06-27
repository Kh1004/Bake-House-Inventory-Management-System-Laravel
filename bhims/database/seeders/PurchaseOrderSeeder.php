<?php

namespace Database\Seeders;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Ingredient;
use App\Models\Supplier;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PurchaseOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Only run if no purchase orders exist
        if (PurchaseOrder::count() > 0) {
            $this->command->info('Purchase orders already exist. Skipping...');
            return;
        }

        // Get required models
        $suppliers = Supplier::all();
        $users = User::all();
        
        if ($suppliers->isEmpty() || $users->isEmpty()) {
            $this->command->error('No suppliers or users found. Please run the SupplierSeeder and UserSeeder first.');
            return;
        }

        // Create purchase orders for different statuses
        $statuses = ['draft', 'ordered', 'received', 'cancelled'];
        $orders = [];
        $now = now();

        foreach ($statuses as $status) {
            for ($i = 0; $i < 3; $i++) {
                $supplier = $suppliers->random();
                $user = $users->random();
                $orderDate = Carbon::now()->subDays(rand(1, 30));
                $expectedDeliveryDate = (clone $orderDate)->addDays(rand(1, 14));
                $receivedAt = in_array($status, ['received', 'cancelled']) ? $expectedDeliveryDate : null;

                $order = PurchaseOrder::create([
                    'po_number' => 'PO-' . strtoupper(uniqid()),
                    'supplier_id' => $supplier->id,
                    'user_id' => $user->id,
                    'order_date' => $orderDate,
                    'expected_delivery_date' => $expectedDeliveryDate,
                    'status' => $status,
                    'notes' => "Test purchase order with status: {$status}",
                    'total_amount' => 0, // Will be updated after items are added
                    'created_at' => $orderDate,
                    'updated_at' => $receivedAt ?? $orderDate,
                ]);

                // Add items to the order
                $this->addItemsToOrder($order, $status);
                $orders[] = $order;
            }
        }

        $this->command->info('Created ' . count($orders) . ' purchase orders with items.');
    }

    /**
     * Add items to a purchase order
     */
    private function addItemsToOrder(PurchaseOrder $order, string $status): void
    {
        $ingredients = Ingredient::inRandomOrder()->limit(rand(2, 8))->get();
        $totalAmount = 0;
        $now = now();

        foreach ($ingredients as $ingredient) {
            $quantity = rand(5, 50);
            $unitPrice = $ingredient->unit_price * (rand(90, 110) / 100); // Randomize price ±10%
            $totalPrice = $quantity * $unitPrice;
            
            // For received orders, set received quantity
            $receivedQty = in_array($status, ['received', 'cancelled']) ? 
                ($status === 'received' ? $quantity : 0) : 
                ($status === 'ordered' ? rand(0, $quantity) : 0);

            // For received orders, set received_quantity to full quantity
            // For cancelled orders, set to 0
            // For other statuses, set to a random value between 0 and quantity
            $receivedQty = match($status) {
                'received' => $quantity,
                'cancelled' => 0,
                default => rand(0, $quantity)
            };

            // Set item status based on received quantity
            $itemStatus = match(true) {
                $status === 'cancelled' => 'cancelled',
                $receivedQty >= $quantity => 'received',
                $receivedQty > 0 => 'partially_received',
                default => 'pending'
            };

            PurchaseOrderItem::create([
                'purchase_order_id' => $order->id,
                'ingredient_id' => $ingredient->id,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'total_price' => $totalPrice,
                'received_quantity' => $receivedQty,
                'status' => $itemStatus,
                'expiry_date' => $status === 'received' ? now()->addMonths(rand(6, 24)) : null,
                'created_at' => $order->created_at,
                'updated_at' => $order->created_at,
            ]);

            $totalAmount += $totalPrice;
        }

        // Update the order total amount and item count
        $order->update([
            'total_amount' => $totalAmount,
            'item_count' => $ingredients->count(),
        ]);

        // Update inventory for received items
        if ($status === 'received') {
            foreach ($order->items as $item) {
                $ingredient = $item->ingredient;
                $ingredient->current_stock += $item->received_quantity;
                $ingredient->save();
            }
        }
    }
}
