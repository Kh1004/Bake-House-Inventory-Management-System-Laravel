<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Recipe;
use App\Models\Ingredient;
use App\Models\Category;

class RecipeSeeder extends Seeder
{
    public function run()
    {
        // Get or create necessary ingredients
        $ingredients = [
            'flour' => $this->getOrCreateIngredient('All-Purpose Flour', 'Flours & Grains', 'kg', 100, 20, 1.20),
            'sugar' => $this->getOrCreateIngredient('Granulated Sugar', 'Sugars & Sweeteners', 'kg', 60, 10, 1.00),
            'yeast' => $this->getOrCreateIngredient('Active Dry Yeast', 'Leavening Agents', 'g', 1000, 200, 0.05),
            'salt' => $this->getOrCreateIngredient('Salt', 'Seasonings', 'kg', 10, 2, 0.50),
            'butter' => $this->getOrCreateIngredient('Unsalted Butter', 'Dairy', 'kg', 40, 10, 5.50),
            'milk' => $this->getOrCreateIngredient('Whole Milk', 'Dairy', 'liter', 50, 20, 1.20),
            'brownSugar' => $this->getOrCreateIngredient('Brown Sugar', 'Sugars & Sweeteners', 'kg', 25, 5, 1.40),
            'eggs' => $this->getOrCreateIngredient('Eggs', 'Eggs & Dairy', 'dozen', 20, 5, 2.50),
            'vanilla' => $this->getOrCreateIngredient('Vanilla Extract', 'Flavorings & Extracts', 'ml', 1000, 200, 0.10),
            'bakingSoda' => $this->getOrCreateIngredient('Baking Soda', 'Leavening Agents', 'g', 1000, 200, 0.02),
            'chocolateChips' => $this->getOrCreateIngredient('Semi-Sweet Chocolate Chips', 'Chocolate & Cocoa', 'kg', 20, 5, 8.50),
        ];

        // Simple White Bread Recipe
        $bread = Recipe::create([
            'name' => 'Classic White Bread',
            'description' => 'A simple and delicious white bread recipe',
            'instructions' => "1. Mix flour, sugar, salt, and yeast in a bowl\n2. Add warm water and mix until a dough forms\n3. Knead for 10 minutes\n4. Let rise for 1 hour\n5. Shape and place in a loaf pan\n6. Let rise for another 30 minutes\n7. Bake at 375°F (190°C) for 30-35 minutes",
            'serving_size' => 1,
            'cost_per_serving' => 1.50,
            'selling_price' => 4.99,
        ]);

        // Attach ingredients to bread recipe
        $bread->ingredients()->attach([
            $ingredients['flour']->id => ['quantity' => 3.25, 'unit_of_measure' => 'cups'],
            $ingredients['sugar']->id => ['quantity' => 2, 'unit_of_measure' => 'tbsp'],
            $ingredients['yeast']->id => ['quantity' => 7, 'unit_of_measure' => 'g'],
            $ingredients['salt']->id => ['quantity' => 1.5, 'unit_of_measure' => 'tsp'],
            $ingredients['butter']->id => ['quantity' => 30, 'unit_of_measure' => 'g'],
            $ingredients['milk']->id => ['quantity' => 1.25, 'unit_of_measure' => 'cups'],
        ]);

        // Chocolate Chip Cookies Recipe
        $cookies = Recipe::create([
            'name' => 'Chocolate Chip Cookies',
            'description' => 'Classic chocolate chip cookies',
            'instructions' => "1. Cream butter and sugars\n2. Add eggs and vanilla\n3. Mix in dry ingredients\n4. Fold in chocolate chips\n5. Drop by spoonfuls onto baking sheet\n6. Bake at 375°F (190°C) for 9-11 minutes",
            'serving_size' => 24,
            'cost_per_serving' => 0.25,
            'selling_price' => 1.99,
        ]);

        // Attach ingredients to cookies recipe
        $cookies->ingredients()->attach([
            $ingredients['flour']->id => ['quantity' => 2.25, 'unit_of_measure' => 'cups'],
            $ingredients['bakingSoda']->id => ['quantity' => 1, 'unit_of_measure' => 'tsp'],
            $ingredients['salt']->id => ['quantity' => 1, 'unit_of_measure' => 'tsp'],
            $ingredients['butter']->id => ['quantity' => 200, 'unit_of_measure' => 'g'],
            $ingredients['sugar']->id => ['quantity' => 100, 'unit_of_measure' => 'g'],
            $ingredients['brownSugar']->id => ['quantity' => 150, 'unit_of_measure' => 'g'],
            $ingredients['eggs']->id => ['quantity' => 2, 'unit_of_measure' => 'large'],
            $ingredients['vanilla']->id => ['quantity' => 2, 'unit_of_measure' => 'tsp'],
            $ingredients['chocolateChips']->id => ['quantity' => 350, 'unit_of_measure' => 'g'],
        ]);
    }

    /**
     * Get or create an ingredient
     *
     * @param string $name
     * @param string $categoryName
     * @param string $unit
     * @param float $currentStock
     * @param float $minStock
     * @param float $unitPrice
     * @return \App\Models\Ingredient
     */
    private function getOrCreateIngredient($name, $categoryName, $unit, $currentStock, $minStock, $unitPrice)
    {
        $category = Category::where('name', $categoryName)->first();
        
        if (!$category) {
            $category = Category::create([
                'name' => $categoryName,
                'description' => $categoryName,
            ]);
        }

        return Ingredient::firstOrCreate(
            ['name' => $name],
            [
                'category_id' => $category->id,
                'unit_of_measure' => $unit,
                'current_stock' => $currentStock,
                'minimum_stock' => $minStock,
                'unit_price' => $unitPrice,
            ]
        );
    }
}
