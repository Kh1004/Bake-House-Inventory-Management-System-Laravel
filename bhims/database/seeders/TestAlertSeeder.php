<?php

namespace Database\Seeders;

use App\Models\AlertConfiguration;
use App\Models\User;
use Illuminate\Database\Seeder;

class TestAlertSeeder extends Seeder
{
    public function run()
    {
        // Get the first user or create one if none exists
        $user = User::first();
        
        if (!$user) {
            $user = User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => bcrypt('password'),
            ]);
        }

        // Create a test alert configuration
        AlertConfiguration::create([
            'user_id' => $user->id,
            'alert_type' => 'low_stock',
            'channels' => ['email', 'in_app'],
            'thresholds' => [
                'warning_level' => 20,
                'critical_level' => 10,
            ],
            'is_active' => true,
            'custom_message' => 'Test low stock alert for {ingredient}. Current stock: {current_quantity} {unit}',
        ]);

        $this->command->info('Test alert configuration created successfully!');
    }
}
