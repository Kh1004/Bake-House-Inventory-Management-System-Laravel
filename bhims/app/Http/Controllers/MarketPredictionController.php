<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\MarketPrediction;
use App\Services\PredictionService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MarketPredictionController extends Controller
{
    protected $predictionService;

    public function __construct(PredictionService $predictionService)
    {
        $this->predictionService = $predictionService;
        $this->middleware('auth:api');
    }

    /**
     * Get demand forecast for a product
     *
     * @param Request $request
     * @param int $productId
     * @return JsonResponse
     */
    public function getDemandForecast(Request $request, $productId): JsonResponse
    {
        try {
            $product = Product::findOrFail($productId);
            $daysAhead = $request->input('days_ahead', 30);
            
            $forecast = $this->predictionService->generateDemandForecast($product, $daysAhead);
            
            return response()->json($forecast);
        } catch (\Exception $e) {
            Log::error('Error in getDemandForecast: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate demand forecast',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get sales trends for a product
     *
     * @param Request $request
     * @param int $productId
     * @return JsonResponse
     */
    public function getSalesTrends(Request $request, $productId): JsonResponse
    {
        try {
            $product = Product::findOrFail($productId);
            $daysBack = $request->input('days_back', 90);
            
            $trends = $this->predictionService->analyzeSalesTrends($product, $daysBack);
            
            return response()->json($trends);
        } catch (\Exception $e) {
            Log::error('Error in getSalesTrends: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to analyze sales trends',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get inventory recommendations
     *
     * @param Request $request
     * @param int $productId
     * @return JsonResponse
     */
    public function getInventoryRecommendations(Request $request, $productId): JsonResponse
    {
        try {
            $product = Product::findOrFail($productId);
            $daysAhead = $request->input('days_ahead', 30);
            
            // Get forecast first
            $forecast = $this->predictionService->generateDemandForecast($product, $daysAhead);
            
            if (!$forecast['success']) {
                return response()->json($forecast);
            }
            
            // Generate recommendations based on forecast
            $recommendations = $this->predictionService->generateInventoryRecommendations($product, $forecast);
            
            return response()->json($recommendations);
        } catch (\Exception $e) {
            Log::error('Error in getInventoryRecommendations: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate inventory recommendations',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get dashboard data
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getDashboardData(Request $request): JsonResponse
    {
        try {
            $productId = $request->input('product_id');
            $daysAhead = $request->input('days_ahead', 30);
            $daysBack = $request->input('days_back', 90);
            
            if (!$productId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product ID is required'
                ], 400);
            }
            
            $product = Product::findOrFail($productId);
            
            $dashboardData = [
                'product' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'current_stock' => $product->current_stock,
                    'safety_stock' => $product->safety_stock,
                    'lead_time_days' => $product->lead_time_days,
                ],
                'date_range' => [
                    'start' => now()->subDays($daysBack)->format('Y-m-d'),
                    'end' => now()->addDays($daysAhead)->format('Y-m-d'),
                    'days_ahead' => $daysAhead,
                    'days_back' => $daysBack,
                ]
            ];
            
            // Get forecast
            $forecast = $this->predictionService->generateDemandForecast($product, $daysAhead);
            $dashboardData['forecast'] = $forecast;
            
            // Get sales trends
            $trends = $this->predictionService->analyzeSalesTrends($product, $daysBack);
            $dashboardData['sales_trends'] = $trends;
            
            // Get inventory recommendations
            if ($forecast['success']) {
                $recommendations = $this->predictionService->generateInventoryRecommendations($product, $forecast);
                $dashboardData['inventory_recommendations'] = $recommendations;
            }
            
            return response()->json([
                'success' => true,
                'data' => $dashboardData
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in getDashboardData: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load dashboard data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
