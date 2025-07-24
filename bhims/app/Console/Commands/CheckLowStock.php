<?php

namespace App\Console\Commands;

use App\Models\Alert;
use App\Models\Ingredient;
use App\Models\User;
use App\Notifications\LowStockNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;

class CheckLowStock extends Command
{
    protected $signature = 'inventory:check-low-stock';
    protected $description = 'Check for ingredients below minimum stock levels and send notifications';

    public function handle()
    {
        Log::info('Starting low stock check');
        
        // Get all ingredients that are below minimum stock
        $lowStockIngredients = Ingredient::where('current_stock', '<=', DB::raw('minimum_stock'))
            ->where(function($query) {
                $query->where('low_stock_notified', false)
                    ->orWhere('last_stock_notification_at', '<', now()->subHours(24));
            })
            ->get();

        if ($lowStockIngredients->isEmpty()) {
            $this->info('No low stock items found.');
            Log::info('No low stock items found');
            return 0;
        }

        $this->info('Found ' . $lowStockIngredients->count() . ' items below minimum stock levels.');
        Log::info('Found ' . $lowStockIngredients->count() . ' low stock items', $lowStockIngredients->pluck('name', 'id')->toArray());

        // Get all users to notify
        $users = User::all();

        if ($users->isEmpty()) {
            $error = 'No users found to notify.';
            $this->error($error);
            Log::error($error);
            return 1;
        }

        Log::info('Found ' . $users->count() . ' users to notify');

        // Send notifications for low stock items
        foreach ($lowStockIngredients as $ingredient) {
            $alertCount = 0;
            
            // Create an alert for each user
            foreach ($users as $user) {
                try {
                    Alert::create([
                        'user_id' => $user->id,
                        'title' => 'Low Stock Alert',
                        'message' => 'Ingredient "' . $ingredient->name . '" is low on stock. Current stock: ' . $ingredient->current_stock . ' ' . ($ingredient->unit ?? 'units') . ' (min: ' . $ingredient->minimum_stock . ' ' . ($ingredient->unit ?? 'units') . ')',
                        'type' => 'low_stock',
                        'priority' => 'high',
                        'is_read' => false,
                        'metadata' => [
                            'ingredient_id' => $ingredient->id,
                            'ingredient_name' => $ingredient->name,
                            'current_stock' => $ingredient->current_stock,
                            'minimum_stock' => $ingredient->minimum_stock,
                            'unit' => $ingredient->unit ?? 'units'
                        ]
                    ]);
                    $alertCount++;
                    
                    Log::info('Created alert for user ' . $user->id . ' for ingredient ' . $ingredient->name);
                } catch (\Exception $e) {
                    $error = 'Failed to create alert for user ' . $user->id . ': ' . $e->getMessage();
                    $this->error($error);
                    Log::error($error, ['user_id' => $user->id, 'ingredient_id' => $ingredient->id]);
                    continue;
                }
            }

            try {
                // Send email notification to all users
                Notification::send($users, new LowStockNotification($ingredient));
                Log::info('Sent email notifications for low stock: ' . $ingredient->name);
            } catch (\Exception $e) {
                $error = 'Failed to send email notification for ingredient ' . $ingredient->id . ': ' . $e->getMessage();
                $this->error($error);
                Log::error($error);
            }

            // Update ingredient notification status
            $ingredient->update([
                'low_stock_notified' => true,
                'last_stock_notification_at' => now(),
            ]);

            $this->line('Created ' . $alertCount . ' alerts and sent notifications for low stock: ' . $ingredient->name);
            Log::info('Updated notification status for ingredient: ' . $ingredient->name);
        }

        $this->info('Low stock notifications sent successfully.');
        Log::info('Low stock check completed successfully');
        return 0;
    }
}