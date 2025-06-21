<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\Category;
use Illuminate\Http\Request;

class LowStockIngredientController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of low stock ingredients.
     */
    public function index(Request $request)
    {
        $query = Ingredient::with('category')
            ->whereColumn('current_stock', '<=', 'minimum_stock')
            ->where('minimum_stock', '>', 0);

        // Search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        // Category filter
        if ($request->has('category') && !empty($request->category)) {
            $query->where('category_id', $request->category);
        }

        $ingredients = $query->latest()->paginate(10);
        $categories = Category::all();
        
        // Set a flag to indicate this is the low stock view
        $isLowStockPage = true;

        // Return JSON response for AJAX requests
        if ($request->ajax() || $request->has('ajax')) {
            $view = view('ingredients.partials.table', compact('ingredients', 'isLowStockPage'))->render();
            $pagination = $ingredients->appends($request->except('page'))->links()->toHtml();
            
            return response()->json([
                'html' => $view,
                'pagination' => $pagination
            ]);
        }

        return view('ingredients.index', compact('ingredients', 'categories', 'isLowStockPage'));
    }
}
