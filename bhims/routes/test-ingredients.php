<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

Route::get('/test-ingredients', function () {
    try {
        $tableInfo = DB::select("SHOW COLUMNS FROM ingredients");
        $ingredients = DB::table('ingredients')->get();
        
        return [
            'table_structure' => $tableInfo,
            'ingredients' => $ingredients,
        ];
    } catch (\Exception $e) {
        return [
            'error' => true,
            'message' => $e->getMessage(),
        ];
    }
});
