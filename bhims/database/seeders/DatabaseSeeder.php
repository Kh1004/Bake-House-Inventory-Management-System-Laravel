<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\PredictionFeedbackSeeder;
use Database\Seeders\PurchaseOrderSeeder;
use Database\Seeders\SalesDataSeeder;
use Database\Seeders\AlertDemoSeeder;
use Database\Seeders\TestAlertsSeeder;
use Database\Seeders\CompetitorAnalysisSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            UserSeeder::class,
            CategorySeeder::class,
            IngredientSeeder::class,
            SupplierSeeder::class,
            RecipeSeeder::class,
            ProductSeeder::class,
            CustomerSeeder::class,
            SalesDataSeeder::class,
            PredictionFeedbackSeeder::class,
            PurchaseOrderSeeder::class,
            TestAlertsSeeder::class,
            CompetitorAnalysisSeeder::class,
        ]);
    }
}
