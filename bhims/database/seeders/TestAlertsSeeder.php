<?php

namespace Database\Seeders;

use App\Models\Alert;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestAlertsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first user or create one if none exists
        $user = User::first();
        
        if (!$user) {
            $user = User::create([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => bcrypt('password'),
            ]);
        }

        // Clear existing alerts
        DB::table('alerts')->truncate();

        // Create test alerts
        $alerts = [
            [
                'title' => 'Low Stock Alert',
                'message' => 'Flour stock is running low. Current quantity: 5kg',
                'type' => 'warning',
                'priority' => 'high',
                'is_read' => false,
                'metadata' => [
                    'ingredient_id' => 1,
                    'current_quantity' => 5,
                    'threshold' => 10,
                    'unit' => 'kg'
                ],
                'created_at' => now()->subHours(2),
                'updated_at' => now()->subHours(2),
            ],
            [
                'title' => 'Expiry Notice',
                'message' => 'Milk will expire in 2 days',
                'type' => 'info',
                'priority' => 'medium',
                'is_read' => false,
                'metadata' => [
                    'ingredient_id' => 2,
                    'expiry_date' => now()->addDays(2)->format('Y-m-d'),
                    'product_name' => 'Fresh Milk'
                ],
                'created_at' => now()->subHours(5),
                'updated_at' => now()->subHours(5),
            ],
            [
                'title' => 'Price Change',
                'message' => 'Price of Sugar has increased by 10%',
                'type' => 'info',
                'priority' => 'low',
                'is_read' => true,
                'read_at' => now()->subDay(),
                'metadata' => [
                    'ingredient_id' => 3,
                    'old_price' => 100,
                    'new_price' => 110,
                    'currency' => 'LKR',
                    'change_percentage' => 10
                ],
                'created_at' => now()->subDay(),
                'updated_at' => now()->subDay(),
            ],
            [
                'title' => 'Inventory Check',
                'message' => 'Scheduled inventory check is due today',
                'type' => 'reminder',
                'priority' => 'medium',
                'is_read' => false,
                'metadata' => [
                    'task_id' => 'INV-2023-001',
                    'due_date' => now()->format('Y-m-d'),
                    'assigned_to' => $user->id
                ],
                'created_at' => now()->subHours(1),
                'updated_at' => now()->subHours(1),
            ],
            [
                'title' => 'New Order Received',
                'message' => 'New order #ORD-2023-1001 has been received',
                'type' => 'success',
                'priority' => 'medium',
                'is_read' => false,
                'metadata' => [
                    'order_id' => 'ORD-2023-1001',
                    'customer' => 'John Doe',
                    'amount' => 2500,
                    'currency' => 'LKR',
                    'items' => 5
                ],
                'created_at' => now()->subMinutes(15),
                'updated_at' => now()->subMinutes(15),
            ],
        ];

        // Create alerts
        foreach ($alerts as $alertData) {
            $alert = new Alert($alertData);
            $alert->user_id = $user->id;
            $alert->save();
        }

        $this->command->info('Test alerts created successfully!');
    }
}
