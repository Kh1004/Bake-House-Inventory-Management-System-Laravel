<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

Route::get('/test-db', function () {
    try {
        $connection = DB::connection();
        $databaseName = $connection->getDatabaseName();
        $ingredientsTableExists = Schema::hasTable('ingredients');
        $categoriesTableExists = Schema::hasTable('categories');
        
        return [
            'database_connection' => 'Connected to database: ' . $databaseName,
            'ingredients_table_exists' => $ingredientsTableExists ? 'Yes' : 'No',
            'categories_table_exists' => $categoriesTableExists ? 'Yes' : 'No',
            'categories_count' => $categoriesTableExists ? DB::table('categories')->count() : 0,
        ];
    } catch (\Exception $e) {
        return [
            'error' => true,
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ];
    }
});
