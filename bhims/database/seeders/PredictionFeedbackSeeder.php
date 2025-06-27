<?php

namespace Database\Seeders;

use App\Models\PredictionFeedback;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PredictionFeedbackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all products and users
        $products = Product::all();
        $users = User::all();
        
        if ($products->isEmpty() || $users->isEmpty()) {
            $this->command->info('No products or users found. Skipping prediction feedback seeding.');
            return;
        }

        $methods = ['moving_average', 'linear_regression', 'arima'];
        
        // Generate feedback for the last 30 days
        for ($i = 0; $i < 30; $i++) {
            $date = Carbon::now()->subDays(30 - $i);
            
            // Create 1-3 feedback entries per day
            $entriesCount = rand(1, 3);
            
            for ($j = 0; $j < $entriesCount; $j++) {
                $product = $products->random();
                $user = $users->random();
                $method = $methods[array_rand($methods)];
                
                // Generate realistic prediction data based on product and method
                $baseQuantity = $product->current_stock > 0 ? 
                    max(1, $product->current_stock * (rand(70, 130) / 100)) : 
                    rand(5, 50);
                
                // Add some randomness based on method
                $predictedQuantity = match($method) {
                    'moving_average' => $baseQuantity * (rand(90, 110) / 100),
                    'linear_regression' => $baseQuantity * (rand(85, 115) / 100),
                    'arima' => $baseQuantity * (rand(80, 120) / 100),
                    default => $baseQuantity
                };
                
                $predictionData = [
                    'date' => $date->toDateString(),
                    'predicted_quantity' => round($predictedQuantity, 2),
                    'method' => $method,
                    'parameters' => [
                        'period' => 30,
                        'confidence_interval' => rand(85, 95) / 100
                    ]
                ];
                
                // Generate actual data with some variance from prediction
                $variance = match($method) {
                    'moving_average' => rand(5, 20),
                    'linear_regression' => rand(10, 25),
                    'arima' => rand(15, 30),
                    default => 20
                };
                
                $actualQuantity = $predictedQuantity * (1 + (rand(-$variance, $variance) / 100));
                $accuracy = max(0, min(100, 100 - (abs($actualQuantity - $predictedQuantity) / max(1, $predictedQuantity) * 100)));
                
                $actualData = [
                    'date' => $date->toDateString(),
                    'actual_quantity' => round($actualQuantity, 2),
                    'variance_percent' => round($variance, 2),
                    'accuracy_percent' => round($accuracy, 2)
                ];
                
                // Calculate accuracy rating (1-5 stars)
                $accuracyRating = match(true) {
                    $accuracy >= 90 => 5,
                    $accuracy >= 80 => 4,
                    $accuracy >= 70 => 3,
                    $accuracy >= 60 => 2,
                    default => 1
                };
                
                // Always include actual data with the calculated values
                $actualData = [
                    'date' => $date->toDateString(),
                    'actual_quantity' => $actualQuantity,
                    'variance_percent' => $variance,
                    'accuracy_percent' => $accuracy,
                    'notes' => 'Simulated actual data with ' . $variance . '% variance',
                    'source' => 'system_generated',
                    'calculation' => [
                        'base_quantity' => $baseQuantity,
                        'predicted_quantity' => $predictedQuantity,
                        'variance_applied' => $variance . '%',
                        'resulting_accuracy' => $accuracy . '%'
                    ]
                ];
                
                // Create the feedback entry
                PredictionFeedback::create([
                    'product_id' => $product->id,
                    'user_id' => $user->id,
                    'prediction_method' => $method,
                    'prediction_date' => $date,
                    'prediction_data' => $predictionData,
                    'actual_data' => $actualData,
                    'accuracy_rating' => $accuracyRating,
                    'user_notes' => rand(1, 10) > 7 ? $this->generateRandomNotes() : null,
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
            }
        }
        
        $this->command->info('Successfully seeded prediction feedback data.');
    }
    
    /**
     * Generate random user notes for feedback
     */
    private function generateRandomNotes(): string
    {
        $phrases = [
            'Prediction was quite accurate for our needs.',
            'The model overestimated demand for this product.',
            'Underestimated the actual sales.',
            'Good prediction overall, but could be improved for weekends.',
            'Very accurate for a weekday prediction.',
            'The prediction helped us avoid stockouts.',
            'Need to adjust for seasonal variations.',
            'Great job on this prediction!',
            'The prediction was off by a significant margin.',
            'Accurate within an acceptable range.'
        ];
        
        return $phrases[array_rand($phrases)];
    }
}