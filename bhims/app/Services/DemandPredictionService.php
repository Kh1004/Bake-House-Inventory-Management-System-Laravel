<?php

namespace App\Services;

use App\Models\SaleProduct;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Phpml\Regression\LeastSquares;
use Phpml\Preprocessing\Normalizer;
use Illuminate\Support\Facades\Http;

class DemandPredictionService
{
    /**
     * Calculate simple moving average for a product
     * 
     * @param int $productId
     * @param int $period Number of days to look back (default: 30 days)
     * @return array
     */
    public function calculateMovingAverage($productId, $period = 30)
    {
        $endDate = Carbon::now()->endOfDay();
        $startDate = $endDate->copy()->subDays($period - 1)->startOfDay();

        // Get sales data for the exact 30-day period
        $dailySales = SaleProduct::select(
                DB::raw('DATE(sale_products.created_at) as date'),
                DB::raw('COALESCE(SUM(quantity), 0) as total_quantity')
            )
            ->where('product_id', $productId)
            ->whereDate('sale_products.created_at', '>=', $startDate)
            ->whereDate('sale_products.created_at', '<=', $endDate)
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total_quantity', 'date')
            ->toArray();

        // Initialize array with all dates in the period set to 0
        $allDates = [];
        $currentDate = $startDate->copy();
        
        while ($currentDate <= $endDate) {
            $dateStr = $currentDate->format('Y-m-d');
            $allDates[$dateStr] = $dailySales[$dateStr] ?? 0;
            $currentDate->addDay();
        }
        
        // Ensure we have exactly $period days of data
        $allDates = array_slice($allDates, -$period, $period, true);

        // Calculate moving average (7-day window)
        $movingAverages = [];
        $values = array_values($allDates);
        $window = 7; // 7-day moving average
        
        for ($i = 0; $i < count($values); $i++) {
            $start = max(0, $i - $window + 1);
            $slice = array_slice($values, $start, $window);
            $movingAverages[] = array_sum($slice) / count($slice);
        }

        // Predict next 7 days
        $lastAverage = end($movingAverages);
        $predictionDays = 7;
        $predictions = [];
        
        $currentDate = $endDate->copy()->addDay();
        for ($i = 0; $i < $predictionDays; $i++) {
            $predictions[$currentDate->format('Y-m-d')] = $lastAverage;
            $currentDate->addDay();
        }

        return [
            'historical' => $allDates,
            'moving_averages' => array_combine(array_keys($allDates), $movingAverages),
            'predictions' => $predictions
        ];
    }

    /**
     * Get demand prediction for a product
     * 
     * @param int $productId
     * @param int $daysAhead Number of days to predict (default: 7)
     * @return array
     */
    public function getDemandPrediction($productId, $daysAhead = 7, $method = 'moving_average', bool $allowFallback = true)
    {
        // Ensure we have consistent historical data (30 days)
        $historicalDays = 30;
        $historicalData = $this->getHistoricalData($productId, $historicalDays);
        
        // Get predictions based on selected method
        $result = [];
        switch (strtolower($method)) {
            case 'arima_api':
                $result = $this->predictWithExternalARIMA($historicalData, $daysAhead, [1, 1, 1], $allowFallback);
                break;
            case 'arima_normal':
                $result = $this->predictWithARIMA($historicalData, $daysAhead);
                break;
            case 'linear_regression':
                $result = $this->predictWithLinearRegression($historicalData, $daysAhead);
                break;
            case 'moving_average':
            default:
                $result = $this->predictWithMovingAverage($historicalData, $daysAhead);
                break;
        }
        
        // Ensure all dates are properly formatted
        $formattedResult = [
            'historical' => [],
            'moving_averages' => [],
            'predictions' => []
        ];
        
        // Format historical data
        foreach ($result['historical'] as $date => $value) {
            $formattedDate = Carbon::parse($date)->format('Y-m-d');
            $formattedResult['historical'][$formattedDate] = (int)$value;
        }
        
        // Format moving averages
        if (isset($result['moving_averages'])) {
            foreach ($result['moving_averages'] as $date => $value) {
                $formattedDate = Carbon::parse($date)->format('Y-m-d');
                $formattedResult['moving_averages'][$formattedDate] = (float)number_format($value, 2, '.', '');
            }
        }
        
        // Format predictions
        if (isset($result['predictions'])) {
            $currentDate = Carbon::now()->addDay();
            $predictionValues = array_values($result['predictions']);
            
            for ($i = 0; $i < $daysAhead && $i < count($predictionValues); $i++) {
                $formattedDate = $currentDate->format('Y-m-d');
                $formattedResult['predictions'][$formattedDate] = (float)number_format($predictionValues[$i], 2, '.', '');
                $currentDate->addDay();
            }
        }
        
        // Propagate method label if provided by the underlying predictor
        if (isset($result['method'])) {
            $formattedResult['method'] = $result['method'];
        }
        
        return $formattedResult;
    }
    
    /**
     * Get historical sales data for a product
     */
    protected function getHistoricalData($productId, $days = 30)
    {
        $endDate = Carbon::now()->endOfDay();
        $startDate = $endDate->copy()->subDays($days - 1)->startOfDay();

        // Get sales data for the exact period
        $dailySales = SaleProduct::select(
                DB::raw('DATE(sale_products.created_at) as date'),
                DB::raw('COALESCE(SUM(quantity), 0) as total_quantity')
            )
            ->where('product_id', $productId)
            ->whereDate('sale_products.created_at', '>=', $startDate)
            ->whereDate('sale_products.created_at', '<=', $endDate)
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total_quantity', 'date')
            ->toArray();

        // Initialize array with all dates in the period set to 0
        $allDates = [];
        $currentDate = $startDate->copy();
        
        while ($currentDate <= $endDate) {
            $dateStr = $currentDate->format('Y-m-d');
            $allDates[$dateStr] = (int)($dailySales[$dateStr] ?? 0);
            $currentDate->addDay();
        }
        
        // Ensure we have exactly $days days of data
        $allDates = array_slice($allDates, 0, $days, true);
        
        return $allDates;
    }
    
    /**
     * Predict using Moving Average
     */
    protected function predictWithMovingAverage($historicalData, $daysAhead = 7)
    {
        $values = array_values($historicalData);
        $dates = array_keys($historicalData);
        $window = 7; // 7-day moving average window
        
        // Calculate moving averages for historical data
        $movingAverages = [];
        for ($i = 0; $i < count($values); $i++) {
            $start = max(0, $i - $window + 1);
            $slice = array_slice($values, $start, min($window, $i + 1));
            $average = count($slice) > 0 ? array_sum($slice) / count($slice) : 0;
            $movingAverages[$dates[$i]] = $average;
        }
        
        // Calculate predictions for future days
        $predictions = [];
        $lastDate = Carbon::parse(end($dates));
        $lastAverage = end($movingAverages);
        
        // Use the last 7 days of moving averages to predict future values
        $recentAverages = array_slice($movingAverages, -7, 7, true);
        $trend = 0;
        
        // Calculate trend based on recent averages if we have enough data
        if (count($recentAverages) >= 2) {
            $values = array_values($recentAverages);
            $trend = ($values[count($values)-1] - $values[0]) / (count($values) - 1);
        }
        
        // Generate predictions with trend
        for ($i = 1; $i <= $daysAhead; $i++) {
            $predictionDate = $lastDate->copy()->addDays($i);
            $predictionValue = max(0, $lastAverage + ($trend * $i));
            $predictions[$predictionDate->format('Y-m-d')] = $predictionValue;
        }
        
        return [
            'historical' => $historicalData,
            'moving_averages' => $movingAverages,
            'predictions' => $predictions
        ];
    }
    
    /**
     * Predict using Linear Regression
     */
    protected function predictWithLinearRegression($historicalData, $daysAhead = 7)
    {
        $values = array_values($historicalData);
        $dates = array_keys($historicalData);
        
        // Prepare data for regression
        $samples = [];
        $targets = [];
        
        foreach ($values as $index => $value) {
            $samples[] = [$index];
            $targets[] = $value;
        }
        
        // Train the model
        $regression = new LeastSquares();
        $regression->train($samples, $targets);
        
        // Make predictions
        $predictions = [];
        $currentDate = Carbon::parse(end($dates))->addDay();
        $lastIndex = count($values) - 1;
        
        for ($i = 1; $i <= $daysAhead; $i++) {
            $prediction = $regression->predict([$lastIndex + $i]);
            $predictions[$currentDate->format('Y-m-d')] = max(0, $prediction); // Ensure non-negative
            $currentDate->addDay();
        }
        
        // Calculate trend line for historical data
        $trendLine = [];
        foreach ($samples as $index => $sample) {
            $trendLine[] = $regression->predict($sample);
        }

        return [
            'historical' => $historicalData,
            'trend_line' => array_combine($dates, $trendLine),
            'predictions' => $predictions,
            'method' => 'Linear Regression'
        ];
    }
    
    /**
     * Predict using ARIMA (AutoRegressive Integrated Moving Average)
     * Note: This is a simplified implementation
     */
    protected function predictWithARIMA($historicalData, $daysAhead = 7)
    {
        // For simplicity, we'll use a combination of moving average and linear regression
        // In a production environment, you would use a proper ARIMA implementation
        
        // First, get the trend using linear regression
        $values = array_values($historicalData);
        $dates = array_keys($historicalData);
        
        // Calculate moving average to smooth the data
        $window = 7;
        $smoothed = [];
        
        foreach ($values as $i => $value) {
            $start = max(0, $i - $window);
            $end = min(count($values), $i + $window + 1);
            $windowValues = array_slice($values, $start, $end - $start);
            $smoothed[] = array_sum($windowValues) / count($windowValues);
        }
        
        // Use the smoothed data for prediction
        $predictions = $this->predictWithLinearRegression(
            array_combine($dates, $smoothed),
            $daysAhead
        );
        
        $predictions['method'] = 'ARIMA (Smoothed)';
        return $predictions;
    }

    /**
     * Predict using external ARIMA microservice (FastAPI)
     */
    protected function predictWithExternalARIMA(array $historicalData, int $daysAhead = 7, array $order = [1, 1, 1], bool $allowFallback = true): array
    {
        $values = array_values($historicalData);
        $dates = array_keys($historicalData);

        $serviceUrl = rtrim(config('services.forecast.url'), '/');
        $timeout = (int) config('services.forecast.timeout', 10);

        try {
            $response = Http::timeout($timeout)
                ->acceptJson()
                ->post($serviceUrl . '/forecast', [
                    'series' => array_map('intval', $values),
                    'dates' => $dates,
                    'steps' => $daysAhead,
                    'order' => $order,
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $forecast = $data['forecast'] ?? [];

                $predictions = [];
                $lastDate = Carbon::parse(end($dates));
                for ($i = 1; $i <= $daysAhead; $i++) {
                    $d = $lastDate->copy()->addDays($i)->format('Y-m-d');
                    $predictions[$d] = isset($forecast[$i - 1]) ? (float) $forecast[$i - 1] : 0.0;
                }

                return [
                    'historical' => $historicalData,
                    'predictions' => $predictions,
                    'method' => 'ARIMA (external)',
                ];
            }
        } catch (\Throwable $e) {
            if (!$allowFallback) {
                throw $e;
            }
        }

        if ($allowFallback) {
            return $this->predictWithARIMA($historicalData, $daysAhead);
        }

        throw new \RuntimeException('ARIMA service unavailable and fallback not allowed');
    }
}
