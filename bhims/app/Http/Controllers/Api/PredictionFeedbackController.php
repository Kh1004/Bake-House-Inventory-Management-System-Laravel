<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PredictionFeedback;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class PredictionFeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store prediction feedback
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'prediction_method' => 'required|string|in:moving_average,linear_regression,arima',
            'prediction_date' => 'required|date',
            'prediction_data' => 'required|array',
            'actual_data' => 'nullable|array',
            'accuracy_rating' => 'nullable|numeric|min:1|max:5',
            'user_notes' => 'nullable|string|max:1000'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }


        $feedback = PredictionFeedback::create([
            'product_id' => $request->product_id,
            'user_id' => Auth::id(),
            'prediction_method' => $request->prediction_method,
            'prediction_date' => $request->prediction_date,
            'prediction_data' => $request->prediction_data,
            'actual_data' => $request->actual_data,
            'accuracy_rating' => $request->accuracy_rating,
            'user_notes' => $request->user_notes
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Feedback submitted successfully',
            'data' => $feedback
        ]);
    }

    /**
     * Get feedback for a product
     */
    public function getProductFeedback($productId)
    {
        $feedback = PredictionFeedback::where('product_id', $productId)
            ->with('user:id,name')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $feedback
        ]);
    }

    /**
     * Get prediction accuracy statistics
     */
    public function getAccuracyStats($productId = null)
    {
        $query = PredictionFeedback::query();
        
        if ($productId) {
            $query->where('product_id', $productId);
        }

        $stats = $query->selectRaw('prediction_method, 
                COUNT(*) as total_predictions,
                AVG(accuracy_rating) as avg_accuracy,
                MAX(created_at) as last_updated')
            ->whereNotNull('accuracy_rating')
            ->groupBy('prediction_method')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
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
