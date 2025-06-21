<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\LowStockIngredientController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

Route::get('/', function () {
    return redirect()->route('dashboard');
})->middleware(['auth', 'verified']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Debug route - remove in production
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

// Test route - should be accessible at /test-route
Route::get('/test-route', function () {
    return 'Test route is working!';
});

// Test low-stock route with closure - no auth for testing
Route::get('/ingredients/low-stock-test', function () {
    return 'Low stock test route is working!';
});

Route::middleware('auth')->group(function () {
    // Main low-stock route
    Route::get('ingredients/low-stock', [LowStockIngredientController::class, 'index'])
        ->name('ingredients.low-stock');
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Ingredient Routes
    Route::resource('ingredients', IngredientController::class);
    
    // Low Stock Ingredients Routes
    Route::get('ingredients/low-stock', [LowStockIngredientController::class, 'index'])
        ->name('ingredients.low-stock');
    
    // Additional Ingredient Routes
    Route::prefix('ingredients')->group(function () {
        Route::get('{ingredient}/adjust-stock', [IngredientController::class, 'showAdjustStock'])
            ->name('ingredients.adjust-stock');
        Route::post('{ingredient}/adjust-stock', [IngredientController::class, 'adjustStock']);
    });
});

require __DIR__.'/auth.php';
