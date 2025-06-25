<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LowStockIngredientController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Home Route
Route::get('/', function () {
    return view('welcome');
});

// Test route
Route::get('/test-low-stock', function() {
    return 'Test route works!';
});

// Test low-stock route (temporarily outside auth)
Route::get('ingredients/low-stock', [IngredientController::class, 'lowStock'])
    ->name('ingredients.low-stock.test');

// Dashboard Routes
Route::prefix('dashboard')->name('dashboard.')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [\App\Http\Controllers\DashboardController::class, 'overview'])->name('overview');
    Route::get('/quick-actions', [\App\Http\Controllers\DashboardController::class, 'quickActions'])->name('quick-actions');
    Route::get('/recent-activities', [\App\Http\Controllers\DashboardController::class, 'recentActivities'])->name('recent-activities');
});

// Legacy dashboard route for backward compatibility
Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'overview'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Authentication Routes
require __DIR__.'/auth.php';

// Test routes (remove in production)
if (app()->environment('local')) {
    require __DIR__.'/test-activity.php';
}

// Authenticated Routes
Route::middleware(['auth'])->group(function () {
    // Test authenticated route
    Route::get('/test-authenticated', function() {
        return 'Authenticated test route works!';
    });

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Ingredients Routes
    Route::resource('ingredients', IngredientController::class);
    Route::get('ingredients/low-stock', [IngredientController::class, 'lowStock'])
        ->name('ingredients.low-stock');
    Route::get('ingredients/{ingredient}/adjust-stock', [IngredientController::class, 'showAdjustStock'])
        ->name('ingredients.adjust-stock');
    Route::post('ingredients/{ingredient}/adjust-stock', [IngredientController::class, 'adjustStock']);

    // Categories Routes
    Route::resource('categories', CategoryController::class);
    
    // Products Routes
    Route::resource('products', \App\Http\Controllers\ProductController::class);
    
    // Suppliers Routes
    Route::resource('suppliers', \App\Http\Controllers\SupplierController::class);
    
    // Sales Routes
    Route::resource('sales', \App\Http\Controllers\SaleController::class);
    
    // Customers Routes
    Route::resource('customers', \App\Http\Controllers\CustomerController::class);
    
    // Reports Routes
    Route::prefix('reports')->group(function () {
        Route::get('/sales', [\App\Http\Controllers\ReportController::class, 'sales'])->name('reports.sales');
        Route::get('/inventory', [\App\Http\Controllers\ReportController::class, 'inventory'])->name('reports.inventory');
    });
    
    // Recipes Routes
    Route::resource('recipes', \App\Http\Controllers\RecipeController::class);
    Route::put('recipes/{recipe}/toggle-status', [\App\Http\Controllers\RecipeController::class, 'toggleStatus'])
        ->name('recipes.toggle-status');
});

// Debug Route (remove in production)
Route::get('/debug/ingredients-table', function () {
    try {
        $columns = DB::select('DESCRIBE ingredients');
        return response()->json([
            'status' => 'success',
            'table' => 'ingredients',
            'columns' => array_column($columns, 'Field')
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
});