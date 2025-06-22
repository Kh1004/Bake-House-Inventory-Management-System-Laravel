<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

Route::get('/check-ingredients', function () {
    try {
        // Get table structure
        $structure = DB::select("SHOW COLUMNS FROM ingredients");
        
        // Get all ingredients with category name
        $ingredients = DB::table('ingredients')
            ->leftJoin('categories', 'ingredients.category_id', '=', 'categories.id')
            ->select('ingredients.*', 'categories.name as category_name')
            ->get();
            
        return response()->json([
            'table_structure' => $structure,
            'ingredients' => $ingredients,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => true,
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});
