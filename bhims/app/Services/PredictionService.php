<?php

namespace App\Services;

use App\Models\DailySale;
use App\Models\MarketPrediction;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PredictionService
{
    /**
     * Generate demand forecast for a product
     *
     * @param Product $product
     * @param int $daysAhead
     * @return array
     */
    public function generateDemandForecast(Product $product, int $daysAhead = 30): array
    {
        // Get historical sales data
        $historicalData = DailySale::where('product_id', $product->id)
            ->where('date', '>=', now()->subYear())
            ->orderBy('date')
            ->get(['date', 'quantity_sold', 'revenue']);

        if ($historicalData->isEmpty()) {
            return [
                'success' => false,
                'message' => 'Insufficient historical data for prediction',
                'predictions' => []
            ];
        }

        // Simple moving average for prediction
        $predictions = [];
        $windowSize = min(7, $historicalData->count()); // 7-day moving average
        $lastSales = $historicalData->pluck('quantity_sold')->toArray();
        
        // Calculate simple moving average
        $sum = array_sum(array_slice($lastSales, -$windowSize));
        $movingAverage = $sum / $windowSize;
        
        // Add some seasonality (example: 10% higher on weekends)
        $currentDate = now()->startOfDay();
        
        for ($i = 1; $i <= $daysAhead; $i++) {
            $predictionDate = $currentDate->copy()->addDays($i);
            
            // Adjust for day of week (example: 10% higher on weekends)
            $dayOfWeek = $predictionDate->dayOfWeek;
            $seasonality = in_array($dayOfWeek, [0, 6]) ? 1.1 : 1.0;
            
            // Simple prediction
            $predictedDemand = round($movingAverage * $seasonality);
            
            // Add some randomness (optional)
            $randomFactor = 0.9 + (mt_rand(0, 20) / 100); // Random factor between 0.9 and 1.1
            $predictedDemand = max(1, round($predictedDemand * $randomFactor));
            
            $predictions[] = [
                'date' => $predictionDate->format('Y-m-d'),
                'predicted_demand' => $predictedDemand,
                'confidence_lower' => max(0, round($predictedDemand * 0.8)),
                'confidence_upper' => round($predictedDemand * 1.2),
                'day_of_week' => $predictionDate->dayName,
            ];
        }

        return [
            'success' => true,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'predictions' => $predictions,
            'metrics' => [
                'moving_average_window' => $windowSize,
                'historical_data_points' => $historicalData->count(),
            ]
        ];
    }

    /**
     * Analyze sales trends for a product
     *
     * @param Product $product
     * @param int $daysBack
     * @return array
     */
    public function analyzeSalesTrends(Product $product, int $daysBack = 90): array
    {
        $endDate = now();
        $startDate = now()->subDays($daysBack);
        
        $salesData = DailySale::where('product_id', $product->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date')
            ->get();

        if ($salesData->isEmpty()) {
            return [
                'success' => false,
                'message' => 'No sales data available for the selected period',
                'trends' => []
            ];
        }

        // Calculate daily, weekly, and monthly trends
        $dailyTrends = [];
        $weeklyTrends = [];
        $monthlyTrends = [];

        foreach ($salesData as $sale) {
            $date = Carbon::parse($sale->date);
            $week = $date->format('Y-W');
            $month = $date->format('Y-m');
            $dayOfWeek = $date->dayOfWeek;
            
            // Daily trends
            $dailyTrends[$date->format('Y-m-d')] = [
                'date' => $date->format('Y-m-d'),
                'quantity_sold' => $sale->quantity_sold,
                'revenue' => $sale->revenue,
                'day_of_week' => $date->dayName,
            ];
            
            // Weekly trends
            if (!isset($weeklyTrends[$week])) {
                $weeklyTrends[$week] = [
                    'week' => $date->startOfWeek()->format('Y-m-d') . ' to ' . $date->endOfWeek()->format('Y-m-d'),
                    'quantity_sold' => 0,
                    'revenue' => 0,
                    'days_count' => 0,
                ];
            }
            $weeklyTrends[$week]['quantity_sold'] += $sale->quantity_sold;
            $weeklyTrends[$week]['revenue'] += $sale->revenue;
            $weeklyTrends[$week]['days_count']++;
            
            // Monthly trends
            if (!isset($monthlyTrends[$month])) {
                $monthlyTrends[$month] = [
                    'month' => $date->format('F Y'),
                    'quantity_sold' => 0,
                    'revenue' => 0,
                    'days_count' => 0,
                ];
            }
            $monthlyTrends[$month]['quantity_sold'] += $sale->quantity_sold;
            $monthlyTrends[$month]['revenue'] += $sale->revenue;
            $monthlyTrends[$month]['days_count']++;
        }

        // Calculate average sales per day of week
        $dayOfWeekAverages = [];
        $dayCounts = array_fill(0, 7, 0);
        $dayTotals = array_fill(0, 7, 0);
        
        foreach ($dailyTrends as $day) {
            $dayOfWeek = Carbon::parse($day['date'])->dayOfWeek;
            $dayTotals[$dayOfWeek] += $day['quantity_sold'];
            $dayCounts[$dayOfWeek]++;
        }
        
        $daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        foreach ($daysOfWeek as $index => $dayName) {
            $count = $dayCounts[$index] > 0 ? $dayCounts[$index] : 1;
            $dayOfWeekAverages[] = [
                'day' => $dayName,
                'average_quantity' => round($dayTotals[$index] / $count, 2),
                'total_quantity' => $dayTotals[$index],
                'day_count' => $dayCounts[$index],
            ];
        }

        return [
            'success' => true,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'date_range' => [
                'start' => $startDate->format('Y-m-d'),
                'end' => $endDate->format('Y-m-d'),
            ],
            'daily_trends' => array_values($dailyTrends),
            'weekly_trends' => array_values($weeklyTrends),
            'monthly_trends' => array_values($monthlyTrends),
            'day_of_week_averages' => $dayOfWeekAverages,
            'total_quantity_sold' => $salesData->sum('quantity_sold'),
            'total_revenue' => $salesData->sum('revenue'),
            'average_daily_sales' => $salesData->avg('quantity_sold'),
        ];
    }

    /**
     * Generate inventory recommendations based on predictions and current stock
     *
     * @param Product $product
     * @param array $predictions
     * @return array
     */
    public function generateInventoryRecommendations(Product $product, array $predictions): array
    {
        if (empty($predictions['predictions'])) {
            return [
                'success' => false,
                'message' => 'No prediction data available',
                'recommendations' => []
            ];
        }

        $leadTime = $product->lead_time_days ?? 7; // Default 7 days lead time
        $safetyStock = $product->safety_stock ?? 10; // Default safety stock
        
        // Calculate average daily demand from predictions
        $totalPredicted = array_sum(array_column($predictions['predictions'], 'predicted_demand'));
        $averageDailyDemand = $totalPredicted / count($predictions['predictions']);
        
        // Calculate reorder point
        $reorderPoint = ($averageDailyDemand * $leadTime) + $safetyStock;
        
        // Check current stock level
        $currentStock = $product->current_stock ?? 0;
        $daysOfStockLeft = $currentStock > 0 ? round($currentStock / $averageDailyDemand, 1) : 0;
        
        // Generate recommendations
        $recommendations = [
            'current_stock' => $currentStock,
            'average_daily_demand' => round($averageDailyDemand, 2),
            'days_of_stock_left' => $daysOfStockLeft,
            'reorder_point' => round($reorderPoint),
            'lead_time_days' => $leadTime,
            'safety_stock' => $safetyStock,
            'suggested_order_quantity' => 0,
            'status' => 'sufficient',
            'message' => 'Stock level is sufficient',
        ];
        
        // Check if we need to reorder
        if ($currentStock <= $reorderPoint) {
            $suggestedOrder = ($averageDailyDemand * ($leadTime + 7)) - $currentStock + $safetyStock;
            $recommendations['suggested_order_quantity'] = max(0, round($suggestedOrder));
            $recommendations['status'] = 'reorder';
            $recommendations['message'] = 'Consider placing a reorder';
        } elseif ($daysOfStockLeft < $leadTime * 1.5) {
            $recommendations['status'] = 'monitor';
            $recommendations['message'] = 'Monitor stock levels closely';
        }

        return [
            'success' => true,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'recommendations' => $recommendations,
            'prediction_period' => [
                'start' => $predictions['predictions'][0]['date'],
                'end' => end($predictions['predictions'])['date'],
                'days' => count($predictions['predictions']),
            ]
        ];
    }

    /**
     * Save predictions to the database
     *
     * @param int $productId
     * @param array $predictions
     * @return bool
     */
    public function savePredictions(int $productId, array $predictions): bool
    {
        try {
            DB::beginTransaction();
            
            // Delete existing predictions for this product and date range
            if (!empty($predictions['predictions'])) {
                $dates = array_column($predictions['predictions'], 'date');
                MarketPrediction::where('product_id', $productId)
                    ->whereIn('prediction_date', $dates)
                    ->delete();
                
                // Insert new predictions
                foreach ($predictions['predictions'] as $prediction) {
                    MarketPrediction::create([
                        'product_id' => $productId,
                        'prediction_date' => $prediction['date'],
                        'predicted_demand' => $prediction['predicted_demand'],
                        'confidence_interval_lower' => $prediction['confidence_lower'],
                        'confidence_interval_upper' => $prediction['confidence_upper'],
                        'historical_data' => json_encode($predictions['metrics'] ?? []),
                        'prediction_metrics' => json_encode([
                            'moving_average_window' => $predictions['metrics']['moving_average_window'] ?? null,
                            'historical_data_points' => $predictions['metrics']['historical_data_points'] ?? null,
                        ]),
                    ]);
                }
            }
            
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to save predictions: ' . $e->getMessage());
            return false;
        }
    }
}