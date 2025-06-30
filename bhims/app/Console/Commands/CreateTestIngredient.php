<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Supplier;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CreateTestIngredient extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ingredient:create-test {--stock=5 : Initial stock quantity} {--name=} {--category=} {--supplier=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a test ingredient with specified stock level for testing alerts';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->option('name') ?? 'Test Ingredient ' . Str::random(5);
        $stock = (float) $this->option('stock');
        
        // Find or create a category
        $categoryName = $this->option('category') ?? 'Test Category';
        $category = Category::firstOrCreate(
            ['name' => $categoryName],
            ['description' => 'Test category for automated testing']
        );
        
        // Find or create a supplier
        $supplierName = $this->option('supplier') ?? 'Test Supplier';
        $supplier = Supplier::firstOrCreate(
            ['name' => $supplierName],
            [
                'contact_person' => 'Test Contact',
                'email' => 'test@example.com',
                'phone' => '1234567890',
                'address' => '123 Test St, Test City'
            ]
        );
        
        // Create the test ingredient
        $ingredient = Ingredient::updateOrCreate(
            ['name' => $name],
            [
                'category_id' => $category->id,
                'supplier_id' => $supplier->id,
                'description' => 'Test ingredient for automated testing',
                'unit_of_measure' => 'kg',
                'current_stock' => $stock,
                'minimum_stock' => 10,
                'cost_per_unit' => 5.99,
                'expiry_date' => now()->addMonths(6),
                'batch_number' => 'TEST-' . Str::upper(Str::random(8)),
                'is_active' => true,
            ]
        );
        
        $this->info('Test ingredient created:');
        $this->line("- Name: {$ingredient->name}");
        $this->line("- Current Stock: {$ingredient->current_stock} {$ingredient->unit_of_measure}");
        $this->line("- Minimum Stock: {$ingredient->minimum_stock} {$ingredient->unit_of_measure}");
        $this->line("- Category: {$category->name}");
        $this->line("- Supplier: {$supplier->name}");
        $this->line("- Batch: {$ingredient->batch_number}");
        
        if ($stock <= 10) {
            $this->warn('WARNING: Low stock level! This should trigger a low stock alert.');
            $this->line('Run "php artisan alerts:check" to test the alert system.');
        }
        
        return 0;
    }
}
