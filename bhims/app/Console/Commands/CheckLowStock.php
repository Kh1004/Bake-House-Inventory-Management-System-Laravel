<?php

namespace App\Console\Commands;

use App\Models\Ingredient;
use App\Models\User;
use App\Notifications\LowStockNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class CheckLowStock extends Command
{
    protected $signature = 'inventory:check-low-stock';
    protected $description = 'Check for ingredients below minimum stock levels and send notifications';

    public function handle()
    {
        // Get all ingredients that are below minimum stock
        $lowStockIngredients = Ingredient::where('current_stock', '<=', DB::raw('minimum_stock'))
            ->where(function($query) {
                $query->where('low_stock_notified', false)
                    ->orWhere('last_stock_notification_at', '<', now()->subHours(24));
            })
            ->get();

        if ($lowStockIngredients->isEmpty()) {
            $this->info('No low stock items found.');
            return 0;
        }

        $this->info('Found ' . $lowStockIngredients->count() . ' items below minimum stock levels.');

        // Get admin users to notify
        $adminUsers = User::whereHas('roles', function($query) {
            $query->where('name', 'admin');
        })->get();

        if ($adminUsers->isEmpty()) {
            $this->error('No admin users found to notify.');
            return 1;
        }

        // Send notifications for low stock items
        foreach ($lowStockIngredients as $ingredient) {
            // Send notification to all admin users
            Notification::send($adminUsers, new LowStockNotification($ingredient));

            // Update ingredient notification status
            $ingredient->update([
                'low_stock_notified' => true,
                'last_stock_notification_at' => now(),
            ]);

            $this->line('Notified about low stock for: ' . $ingredient->name);
        }

        $this->info('Low stock notifications sent successfully.');
        return 0;
    }
}