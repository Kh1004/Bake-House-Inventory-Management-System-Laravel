<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\PredictionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MarketPredictionDashboardController extends Controller
{
    protected $predictionService;

    public function __construct(PredictionService $predictionService)
    {
        $this->middleware('auth');
        $this->predictionService = $predictionService;
    }

    /**
     * Display the Market Prediction Dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get all active products for the dropdown
        $products = Product::where('is_active', true)
            ->orderBy('name')
            ->get();
        
        // If there are no products, return the view with an empty collection
        if ($products->isEmpty()) {
            return view('dashboard.market-prediction', [
                'products' => collect(),
                'selectedProduct' => null
            ]);
        }
        
        // Get the first product by default
        $selectedProduct = $products->first();
        
        return view('dashboard.market-prediction', [
            'products' => $products,
            'selectedProduct' => $selectedProduct
        ]);
    }

    /**
     * Get dashboard data for a specific product.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDashboardData(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $productId = $request->input('product_id');
        $product = Product::findOrFail($productId);

        try {
            // Get demand forecast
            $forecast = $this->predictionService->generateDemandForecast($product);
            
            // Get sales trends
            $trends = $this->predictionService->analyzeSalesTrends($product);
            
            // Get inventory recommendations - Fixed: Pass both product and forecast
            $recommendations = $this->predictionService->generateInventoryRecommendations($product, $forecast);
            
            // Save the prediction to the database - Fixed: Use savePredictions instead of savePrediction
            $this->predictionService->savePredictions($product->id, $forecast);

            return response()->json([
                'success' => true,
                'product' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'current_stock' => $product->current_stock ?? 0,
                    'unit' => $product->unit,
                    'reorder_level' => $product->reorder_level
                ],
                'forecast' => $forecast,
                'trends' => $trends,
                'recommendations' => $recommendations
            ]);

        } catch (\Exception $e) {
            \Log::error('Error in getDashboardData: ' . $e->getMessage(), [
                'product_id' => $productId,
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load dashboard data. Please try again later.',
                'error' => config('app.debug') ? $e->getMessage() : 'An error occurred'
            ], 500);
        }
    }
}
