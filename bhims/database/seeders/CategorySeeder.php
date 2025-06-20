<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Flours & Grains', 'description' => 'Various types of flours and grains'],
            ['name' => 'Sugars & Sweeteners', 'description' => 'Different types of sugars and sweetening agents'],
            ['name' => 'Dairy', 'description' => 'Milk, butter, cream, and other dairy products'],
            ['name' => 'Fats & Oils', 'description' => 'Oils, shortenings, and other fats'],
            ['name' => 'Leavening Agents', 'description' => 'Yeast, baking powder, baking soda'],
            ['name' => 'Chocolate & Cocoa', 'description' => 'Chocolate, cocoa powder, and related products'],
            ['name' => 'Nuts & Dried Fruits', 'description' => 'Various nuts and dried fruits'],
            ['name' => 'Flavorings & Extracts', 'description' => 'Vanilla, almond extracts, and other flavorings'],
            ['name' => 'Spices & Seasonings', 'description' => 'Cinnamon, nutmeg, salt, and other seasonings'],
            ['name' => 'Fruits & Vegetables', 'description' => 'Fresh or frozen fruits and vegetables'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
