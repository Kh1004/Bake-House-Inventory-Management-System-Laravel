<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Models\CompetitorAnalysis;

class CompetitorAnalysisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Competitors
        $competitors = [
            'Bakery Delight',
            'Fresh Bakes',
            'Artisan Bakery',
            'Sweet Treats',
            'Modern Bakery'
        ];

        // Products
        $products = [
            'White Bread',
            'Whole Wheat Bread',
            'Croissant',
            'Bagel',
            'Muffin',
            'Cake',
            'Pastry'
        ];

        // Locations
        $locations = [
            'Colombo',
            'Kandy',
            'Galle',
            'Kurunegala',
            'Negombo'
        ];

        // Generate sample data for the last 90 days
        $startDate = now()->subDays(90);
        $endDate = now();

        $date = $startDate;
        while ($date <= $endDate) {
            foreach ($competitors as $competitor) {
                // Randomly select 2-4 products for each competitor per day
                $selectedProducts = array_rand($products, rand(2, 4));
                if (!is_array($selectedProducts)) {
                    $selectedProducts = [$selectedProducts];
                }

                foreach ($selectedProducts as $productIndex) {
                    $product = $products[$productIndex];
                    
                    // Random price variation (10-50% of base price)
                    $basePrices = [
                        'White Bread' => 200,
                        'Whole Wheat Bread' => 250,
                        'Croissant' => 300,
                        'Bagel' => 150,
                        'Muffin' => 180,
                        'Cake' => 500,
                        'Pastry' => 220
                    ];

                    $priceVariation = rand(-50, 50) / 100;
                    $price = $basePrices[$product] * (1 + $priceVariation);

                    // Random location
                    $location = $locations[array_rand($locations)];

                    // Random notes for some entries
                    $notes = rand(0, 2) === 0 ? $this->generateRandomNotes() : null;

                    CompetitorAnalysis::create([
                        'competitor_name' => $competitor,
                        'product_name' => $product,
                        'price' => $price,
                        'currency' => 'LKR',
                        'location' => $location,
                        'notes' => $notes,
                        'analysis_date' => $date->format('Y-m-d')
                    ]);
                }
            }
            $date->addDay();
        }

        echo "Successfully seeded competitor analyses data.\n";
    }

    private function generateRandomNotes(): string
    {
        $notes = [
            'Promotion running this week',
            'New product launch',
            'Seasonal discount',
            'Special holiday pricing',
            'Limited time offer',
            'Price increase announced',
            'Competitor closed temporarily',
            'New store opening',
            'Staff training day'
        ];

        return $notes[array_rand($notes)];
    }
}
