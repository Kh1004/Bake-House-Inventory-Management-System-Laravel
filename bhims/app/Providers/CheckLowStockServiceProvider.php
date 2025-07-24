<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class CheckLowStockServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Only run in web environment
        if (app()->runningInConsole()) {
            return;
        }

        // Run the command on every request
        Artisan::call('inventory:check-low-stock');
    }
}
