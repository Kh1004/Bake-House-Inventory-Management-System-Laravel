<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AlertConfigurationController;

// Alert Configuration Routes
Route::middleware(['auth:sanctum'])->group(function () {
    // Get all alert configurations for the authenticated user
    Route::get('/alert-configurations', [AlertConfigurationController::class, 'index']);
    
    // Get available alert types and their default configurations
    Route::get('/alert-configurations/types', [AlertConfigurationController::class, 'getAlertTypes']);
    
    // Create a new alert configuration
    Route::post('/alert-configurations', [AlertConfigurationController::class, 'store']);
    
    // Get a specific alert configuration
    Route::get('/alert-configurations/{alertConfiguration}', [AlertConfigurationController::class, 'show']);
    
    // Update an alert configuration
    Route::put('/alert-configurations/{alertConfiguration}', [AlertConfigurationController::class, 'update']);
    
    // Delete an alert configuration
    Route::delete('/alert-configurations/{alertConfiguration}', [AlertConfigurationController::class, 'destroy']);
});
