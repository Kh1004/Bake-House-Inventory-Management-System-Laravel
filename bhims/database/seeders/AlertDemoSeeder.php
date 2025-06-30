<?php

namespace Database\Seeders;

use App\Models\AlertConfiguration;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AlertDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first user or create one if none exists
        $user = User::first() ?? User::factory()->create();

        // Create sample alert configurations
        $alertConfigs = [
            [
                'user_id' => $user->id,
                'alert_type' => 'low_stock',
                'channels' => ['email', 'in_app'],
                'thresholds' => [
                    'warning' => 20,
                    'critical' => 10
                ],
                'is_active' => true,
                'custom_message' => 'Low stock alert for {ingredient}. Current stock: {current_quantity} {unit} (Threshold: {threshold} {unit})',
            ],
            [
                'user_id' => $user->id,
                'alert_type' => 'expiry_alert',
                'channels' => ['email', 'sms'],
                'thresholds' => [
                    'days_before' => 7
                ],
                'is_active' => true,
                'custom_message' => 'Ingredient {ingredient} will expire on {expiry_date}',
            ],
            [
                'user_id' => $user->id,
                'alert_type' => 'price_change',
                'channels' => ['email'],
                'thresholds' => [
                    'percentage' => 10
                ],
                'is_active' => true,
                'custom_message' => 'Price change alert for {ingredient}. New price: {new_price}, Old price: {old_price}',
            ]
        ];

        foreach ($alertConfigs as $config) {
            AlertConfiguration::updateOrCreate(
                ['user_id' => $user->id, 'alert_type' => $config['alert_type']],
                $config
            );
        }

        $this->command->info('Demo alert configurations have been created!');
    }
}
