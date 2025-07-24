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
        // This provider is no longer needed as we've moved the logic to the ShareAlerts middleware
        // to ensure the stock check runs on every request
    }
}
