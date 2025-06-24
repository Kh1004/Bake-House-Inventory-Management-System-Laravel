<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Recipe;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Get recipes
        $breadRecipe = Recipe::where('name', 'Classic White Bread')->first();
        $cookiesRecipe = Recipe::where('name', 'Chocolate Chip Cookies')->first();
        
        // Get categories
        $breadCategory = Category::where('name', 'Flours & Grains')->first();
        $cookiesCategory = Category::where('name', 'Chocolate & Cocoa')->first();
        $pastryCategory = Category::where('name', 'Fats & Oils')->first();

        $products = [
            [
                'name' => 'Classic White Bread Loaf',
                'sku' => 'BREAD-WHITE-001',
                'description' => 'Freshly baked white bread loaf, perfect for sandwiches or toast',
                'recipe_id' => $breadRecipe ? $breadRecipe->id : null,
                'cost_price' => 1.50,
                'selling_price' => 4.99,
                'current_stock' => 50,
                'minimum_stock' => 10,
                'category_id' => $breadCategory->id, // Flours & Grains
            ],
            [
                'name' => 'Chocolate Chip Cookies (6pk)',
                'sku' => 'COOKIE-CC-6PK',
                'description' => 'Freshly baked chocolate chip cookies, pack of 6',
                'recipe_id' => $cookiesRecipe ? $cookiesRecipe->id : null,
                'cost_price' => 1.50,
                'selling_price' => 5.99,
                'current_stock' => 30,
                'minimum_stock' => 15,
                'category_id' => $cookiesCategory->id, // Chocolate & Cocoa
            ],
            [
                'name' => 'Artisan Sourdough Loaf',
                'sku' => 'BREAD-SOUR-001',
                'description' => 'Traditional sourdough bread with a crisp crust and chewy interior',
                'recipe_id' => null,
                'cost_price' => 2.00,
                'selling_price' => 6.99,
                'current_stock' => 25,
                'minimum_stock' => 8,
                'category_id' => $breadCategory->id, // Flours & Grains
            ],
            [
                'name' => 'Whole Grain Bread Loaf',
                'sku' => 'BREAD-WG-001',
                'description' => 'Healthy whole grain bread packed with seeds and nuts',
                'recipe_id' => null,
                'cost_price' => 1.80,
                'selling_price' => 5.99,
                'current_stock' => 30,
                'minimum_stock' => 10,
                'category_id' => $breadCategory->id, // Flours & Grains
            ],
            [
                'name' => 'Croissant',
                'sku' => 'PASTRY-CROI-001',
                'description' => 'Buttery, flaky French croissant',
                'recipe_id' => null,
                'cost_price' => 0.75,
                'selling_price' => 2.99,
                'current_stock' => 60,
                'minimum_stock' => 20,
                'category_id' => $pastryCategory->id, // Fats & Oils
            ],
        ];

        foreach ($products as $product) {
            try {
                Product::create($product);
            } catch (\Exception $e) {
                $this->command->error('Error creating product ' . ($product['name'] ?? 'unknown') . ': ' . $e->getMessage());
            }
        }
    }
}
