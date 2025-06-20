<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ingredient;
use App\Models\Category;

class IngredientSeeder extends Seeder
{
    public function run()
    {
        // Get category IDs
        $flourCategory = Category::where('name', 'Flours & Grains')->first();
        $sugarCategory = Category::where('name', 'Sugars & Sweeteners')->first();
        $dairyCategory = Category::where('name', 'Dairy')->first();
        $fatsCategory = Category::where('name', 'Fats & Oils')->first();
        $leaveningCategory = Category::where('name', 'Leavening Agents')->first();
        $chocolateCategory = Category::where('name', 'Chocolate & Cocoa')->first();
        $flavoringsCategory = Category::where('name', 'Flavorings & Extracts')->first();
        $spicesCategory = Category::where('name', 'Spices & Seasonings')->first();
        $eggsCategory = Category::updateOrCreate(
            ['name' => 'Eggs & Dairy'],
            ['description' => 'Eggs and dairy products']
        );
        $saltCategory = Category::updateOrCreate(
            ['name' => 'Seasonings'],
            ['description' => 'Salt, spices, and seasonings']
        );

        $ingredients = [
            // Flours & Grains
            [
                'name' => 'All-Purpose Flour',
                'category_id' => $flourCategory->id,
                'unit_of_measure' => 'kg',
                'current_stock' => 100,
                'minimum_stock' => 20,
                'unit_price' => 1.20,
            ],
            [
                'name' => 'Bread Flour',
                'category_id' => $flourCategory->id,
                'unit_of_measure' => 'kg',
                'current_stock' => 50,
                'minimum_stock' => 15,
                'unit_price' => 1.50,
            ],
            [
                'name' => 'Whole Wheat Flour',
                'category_id' => $flourCategory->id,
                'unit_of_measure' => 'kg',
                'current_stock' => 30,
                'minimum_stock' => 10,
                'unit_price' => 1.80,
            ],
            
            // Sugars & Sweeteners
            [
                'name' => 'Granulated Sugar',
                'category_id' => $sugarCategory->id,
                'unit_of_measure' => 'kg',
                'current_stock' => 60,
                'minimum_stock' => 10,
                'unit_price' => 1.00,
            ],
            [
                'name' => 'Powdered Sugar',
                'category_id' => $sugarCategory->id,
                'unit_of_measure' => 'kg',
                'current_stock' => 30,
                'minimum_stock' => 5,
                'unit_price' => 1.30,
            ],
            [
                'name' => 'Brown Sugar',
                'category_id' => $sugarCategory->id,
                'unit_of_measure' => 'kg',
                'current_stock' => 25,
                'minimum_stock' => 5,
                'unit_price' => 1.40,
            ],
            [
                'name' => 'Honey',
                'category_id' => $sugarCategory->id,
                'unit_of_measure' => 'kg',
                'current_stock' => 10,
                'minimum_stock' => 2,
                'unit_price' => 4.50,
            ],
            
            // Dairy
            [
                'name' => 'Unsalted Butter',
                'category_id' => $dairyCategory->id,
                'unit_of_measure' => 'kg',
                'current_stock' => 40,
                'minimum_stock' => 10,
                'unit_price' => 5.50,
            ],
            [
                'name' => 'Whole Milk',
                'category_id' => $dairyCategory->id,
                'unit_of_measure' => 'liter',
                'current_stock' => 50,
                'minimum_stock' => 20,
                'unit_price' => 1.20,
            ],
            [
                'name' => 'Eggs',
                'category_id' => $eggsCategory->id,
                'unit_of_measure' => 'dozen',
                'current_stock' => 20,
                'minimum_stock' => 5,
                'unit_price' => 2.50,
            ],
            [
                'name' => 'Buttermilk',
                'category_id' => $dairyCategory->id,
                'unit_of_measure' => 'liter',
                'current_stock' => 20,
                'minimum_stock' => 5,
                'unit_price' => 1.50,
            ],
            [
                'name' => 'Heavy Cream',
                'category_id' => $dairyCategory->id,
                'unit_of_measure' => 'liter',
                'current_stock' => 15,
                'minimum_stock' => 5,
                'unit_price' => 3.50,
            ],
            
            // Leavening Agents
            [
                'name' => 'Active Dry Yeast',
                'category_id' => $leaveningCategory->id,
                'unit_of_measure' => 'g',
                'current_stock' => 1000,
                'minimum_stock' => 200,
                'unit_price' => 0.05,
            ],
            [
                'name' => 'Baking Powder',
                'category_id' => $leaveningCategory->id,
                'unit_of_measure' => 'g',
                'current_stock' => 2000,
                'minimum_stock' => 500,
                'unit_price' => 0.03,
            ],
            [
                'name' => 'Baking Soda',
                'category_id' => $leaveningCategory->id,
                'unit_of_measure' => 'g',
                'current_stock' => 1000,
                'minimum_stock' => 200,
                'unit_price' => 0.02,
            ],
        ];

        foreach ($ingredients as $ingredient) {
            Ingredient::create($ingredient);
        }
    }
}
