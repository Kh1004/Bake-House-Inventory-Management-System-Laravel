<?php

namespace App\Console\Commands;

use App\Models\AlertConfiguration;
use App\Models\Ingredient;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use App\Notifications\AlertTriggered;

class CheckAlerts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alerts:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and trigger alerts based on configured rules';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting alert checks...');
        
        // Get all active alert configurations
        $configs = AlertConfiguration::where('is_active', true)->get();
        
        foreach ($configs as $config) {
            try {
                $this->checkAlert($config);
            } catch (\Exception $e) {
                Log::error("Error processing alert {$config->id}: " . $e->getMessage());
                $this->error("Error processing alert {$config->id}: " . $e->getMessage());
            }
        }
        
        $this->info('Alert checks completed.');
    }
    
    /**
     * Check a single alert configuration and trigger if needed
     */
    protected function checkAlert(AlertConfiguration $config)
    {
        switch ($config->alert_type) {
            case 'low_stock':
                $this->checkLowStockAlert($config);
                break;
                
            case 'expiry_alert':
                $this->checkExpiryAlert($config);
                break;
                
            case 'price_change':
                $this->checkPriceChangeAlert($config);
                break;
        }
    }
    
    /**
     * Check low stock alert
     */
    protected function checkLowStockAlert(AlertConfiguration $config)
    {
        $thresholds = $config->thresholds;
        $warningLevel = $thresholds['warning_level'] ?? 20;
        $criticalLevel = $thresholds['critical_level'] ?? 10;
        
        // Find ingredients below thresholds
        $ingredients = Ingredient::where('current_stock', '<=', $criticalLevel)
            ->orWhere('current_stock', '<=', $warningLevel)
            ->get();
            
        foreach ($ingredients as $ingredient) {
            $level = $ingredient->current_stock <= $criticalLevel ? 'critical' : 'warning';
            $threshold = $level === 'critical' ? $criticalLevel : $warningLevel;
            
            $this->triggerAlert($config, [
                'level' => $level,
                'ingredient' => $ingredient->name,
                'current_quantity' => $ingredient->current_stock,
                'threshold' => $threshold,
                'unit' => $ingredient->unit_of_measure
            ]);
        }
    }
    
    /**
     * Check expiry alert
     */
    protected function checkExpiryAlert(AlertConfiguration $config)
    {
        $daysBefore = $config->thresholds['days_before'] ?? 7;
        $expiryDate = Carbon::now()->addDays($daysBefore);
        
        // Find ingredients expiring soon
        $ingredients = Ingredient::whereDate('expiry_date', '<=', $expiryDate)
            ->where('expiry_date', '>=', now())
            ->get();
            
        foreach ($ingredients as $ingredient) {
            $daysUntilExpiry = now()->diffInDays($ingredient->expiry_date);
            
            $this->triggerAlert($config, [
                'ingredient' => $ingredient->name,
                'expiry_date' => $ingredient->expiry_date->format('Y-m-d'),
                'days_until_expiry' => $daysUntilExpiry,
                'batch_number' => $ingredient->batch_number
            ]);
        }
    }
    
    /**
     * Check price change alert
     */
    protected function checkPriceChangeAlert(AlertConfiguration $config)
    {
        // This would need to be implemented based on your price history tracking
        // For now, it's a placeholder
        $this->warn('Price change alerts are not yet implemented');
    }
    
    /**
     * Trigger an alert notification
     */
    protected function triggerAlert(AlertConfiguration $config, array $data)
    {
        try {
            $user = $config->user;
            
            // Send notification through all configured channels
            foreach ($config->channels as $channel) {
                $user->notify(new AlertTriggered($config, $data, $channel));
            }
            
            $this->info("Alert triggered: {$config->alert_type} for user {$user->id}");
            
        } catch (\Exception $e) {
            Log::error("Error triggering alert {$config->id}: " . $e->getMessage());
            $this->error("Error triggering alert {$config->id}: " . $e->getMessage());
        }
    }
}
