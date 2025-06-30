<?php

namespace App\Console\Commands;

use App\Models\AlertConfiguration;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TestAlertSetup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alerts:test-setup {--user= : ID of the user to create the alert for}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a test alert configuration for testing purposes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->option('user') ?? 1; // Default to user ID 1 if not specified
        
        // Find or create a test user
        $user = User::find($userId);
        
        if (!$user) {
            $this->error("User with ID {$userId} not found.");
            return 1;
        }
        
        $this->info("Creating test alert configuration for user: {$user->name} (ID: {$user->id})");
        
        // Create a test low stock alert
        $alert = AlertConfiguration::updateOrCreate(
            [
                'user_id' => $user->id,
                'alert_type' => 'low_stock',
            ],
            [
                'channels' => ['email', 'in_app'],
                'thresholds' => [
                    'warning_level' => 20,
                    'critical_level' => 10,
                ],
                'is_active' => true,
                'custom_message' => 'This is a test alert configuration. Please check your stock levels.',
            ]
        );
        
        $this->info('Test alert configuration created:');
        $this->line("- Type: low_stock");
        $this->line("- Channels: " . implode(', ', $alert->channels));
        $this->line("- Warning Level: " . ($alert->thresholds['warning_level'] ?? 'N/A'));
        $this->line("- Critical Level: " . ($alert->thresholds['critical_level'] ?? 'N/A'));
        $this->line("- Active: " . ($alert->is_active ? 'Yes' : 'No'));
        $this->line("- Custom Message: " . $alert->custom_message);
        
        $this->info("\nTo test the alert, run: php artisan alerts:check");
        
        return 0;
    }
}
