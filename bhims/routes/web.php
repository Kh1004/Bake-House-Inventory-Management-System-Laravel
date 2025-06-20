<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\IngredientController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Ingredient Routes
    Route::resource('ingredients', IngredientController::class);
    
    // Additional Ingredient Routes
    Route::prefix('ingredients')->group(function () {
        Route::get('{ingredient}/adjust-stock', [IngredientController::class, 'showAdjustStock'])->name('ingredients.adjust-stock');
        Route::post('{ingredient}/adjust-stock', [IngredientController::class, 'adjustStock']);
        Route::get('low-stock', [IngredientController::class, 'lowStock'])->name('ingredients.low-stock');
    });
});

require __DIR__.'/auth.php';
