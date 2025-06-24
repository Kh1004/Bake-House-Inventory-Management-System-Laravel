<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\Sale;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Get low stock ingredients
        $lowStockIngredients = Ingredient::where('current_stock', '<=', DB::raw('minimum_stock'))
            ->orderBy('current_stock', 'asc')
            ->limit(10)
            ->get();

        // Get recent sales
        $recentSales = Sale::with(['customer', 'user'])
            ->latest()
            ->take(5)
            ->get();

        // Get sales data for the current month
        $currentMonthSales = Sale::whereMonth('created_at', now()->month)
            ->sum('total');

        // Get top selling products
        $topProducts = Product::select([
                'products.id',
                'products.name',
                DB::raw('COALESCE(SUM(sale_products.quantity), 0) as total_quantity'),
                DB::raw('COALESCE(SUM(sale_products.quantity * sale_products.unit_price), 0) as total_revenue')
            ])
            ->leftJoin('sale_products', 'products.id', '=', 'sale_products.product_id')
            ->leftJoin('sales', 'sales.id', '=', 'sale_products.sale_id')
            ->whereMonth('sales.created_at', now()->month)
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_quantity', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard', [
            'lowStockIngredients' => $lowStockIngredients,
            'recentSales' => $recentSales,
            'currentMonthSales' => $currentMonthSales,
            'topProducts' => $topProducts,
        ]);
    }

    public function lowStockReport()
    {
        $ingredients = Ingredient::where('current_stock', '<=', DB::raw('minimum_stock'))
            ->orderBy('current_stock', 'asc')
            ->paginate(20);

        return view('reports.low-stock', [
            'ingredients' => $ingredients
        ]);
    }
}
