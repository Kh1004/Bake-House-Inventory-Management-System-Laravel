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

// Dashboard Route
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Authentication Routes
require __DIR__.'/auth.php';

// Authenticated Routes
Route::middleware(['auth'])->group(function () {
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
    
    // Suppliers Routes
    Route::resource('suppliers', \App\Http\Controllers\SupplierController::class);
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