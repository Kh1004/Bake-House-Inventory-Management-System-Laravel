<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Artisan;
use App\Http\View\Composers\AlertComposer;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register the alert composer for the header
        View::composer('layouts.partials.header', AlertComposer::class);
        
        // Run low stock check on every page load
        if (!app()->runningInConsole()) {
            try {
                Artisan::call('inventory:check-low-stock');
            } catch (\Exception $e) {
                // Log the error but don't break the application
                \Log::error('Failed to check low stock: ' . $e->getMessage());
            }
        }
    }
}
