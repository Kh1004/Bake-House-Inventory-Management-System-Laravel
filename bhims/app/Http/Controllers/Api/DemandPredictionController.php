<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DemandPredictionService;
use Illuminate\Http\Request;

class DemandPredictionController extends Controller
{
    protected $predictionService;

    public function __construct(DemandPredictionService $predictionService)
    {
        $this->predictionService = $predictionService;
    }

    /**
     * Get demand prediction for a product
     * 
     * @param Request $request
     * @param int $productId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProductPrediction($productId, Request $request)
    {
        try {
            $daysAhead = (int)$request->input('days_ahead', 7);
            $method = $request->input('method', 'moving_average');
            
            // Validate method
            if (!in_array($method, ['moving_average', 'linear_regression', 'arima'])) {
                $method = 'moving_average';
            }
            
            $prediction = $this->predictionService->getDemandPrediction(
                $productId, 
                $daysAhead,
                $method
            );
            
            return response()->json([
                'success' => true,
                'data' => $prediction,
                'method' => $method,
                'product_id' => $productId,
                'prediction_date' => now()->toDateTimeString()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate prediction',
                'error' => $e->getMessage(),
                'trace' => config('app.debug') ? $e->getTraceAsString() : null
            ], 500);
        }
    }
}
