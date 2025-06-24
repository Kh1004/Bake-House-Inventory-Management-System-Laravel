<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Category;
use App\Models\Product;

class CheckRelationships extends Command
{
    protected $signature = 'check:relationships';
    protected $description = 'Check the relationships between categories and products';

    public function handle()
    {
        $this->info('Checking category-product relationships...');
        
        // Check categories with product counts
        $this->info("\nCategories with product counts:");
        $categories = Category::withCount('products')->get();
        
        if ($categories->isEmpty()) {
            $this->warn('No categories found!');
        } else {
            $this->table(
                ['ID', 'Name', 'Active', 'Product Count'],
                $categories->map(function ($category) {
                    return [
                        'id' => $category->id,
                        'name' => $category->name,
                        'is_active' => $category->is_active ? 'Yes' : 'No',
                        'products_count' => $category->products_count
                    ];
                })
            );
        }
        
        // Check products with their categories
        $this->info("\nSample of products with their categories:");
        $products = Product::with('category')->take(5)->get();
        
        if ($products->isEmpty()) {
            $this->warn('No products found!');
        } else {
            $this->table(
                ['ID', 'Name', 'SKU', 'Category', 'Active'],
                $products->map(function ($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'sku' => $product->sku,
                        'category' => $product->category ? $product->category->name : 'None',
                        'is_active' => $product->is_active ? 'Yes' : 'No',
                    ];
                })
            );
        }
        
        // Check for any products without categories
        $uncategorizedCount = Product::whereNull('category_id')->count();
        if ($uncategorizedCount > 0) {
            $this->warn("\nWarning: Found $uncategorizedCount products without a category!");
        } else {
            $this->info("\nAll products have categories assigned.");
        }
        
        return Command::SUCCESS;
    }
}
