<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\Sale;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Activity;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Show the dashboard overview
     *
     * @return \Illuminate\View\View
     */
    public function overview()
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

        // Get recent activities with pagination
        $recentActivities = Activity::with('causer')
            ->latest()
            ->paginate(10);

        return view('dashboard.overview', [
            'lowStockIngredients' => $lowStockIngredients,
            'recentSales' => $recentSales,
            'currentMonthSales' => $currentMonthSales,
            'topProducts' => $topProducts,
            'recentActivities' => $recentActivities
        ]);
    }

    /**
     * Show quick actions dashboard
     * 
     * @return \Illuminate\View\View
     */
    public function quickActions()
    {
        $quickActions = [
            [
                'title' => 'Add New Ingredient',
                'description' => 'Add a new ingredient to your inventory',
                'icon' => 'plus-circle',
                'url' => route('ingredients.create'),
                'color' => 'indigo'
            ],
            [
                'title' => 'Record New Sale',
                'description' => 'Record a new sale transaction',
                'icon' => 'shopping-cart',
                'url' => route('sales.create'),
                'color' => 'green'
            ],
            [
                'title' => 'Check Low Stock',
                'description' => 'View ingredients that need restocking',
                'icon' => 'exclamation-circle',
                'url' => route('ingredients.low-stock'),
                'color' => 'yellow'
            ],
            [
                'title' => 'View Reports',
                'description' => 'Generate and view sales and inventory reports',
                'icon' => 'chart-bar',
                'url' => '#',
                'color' => 'purple'
            ]
        ];

        return view('dashboard.quick-actions', [
            'quickActions' => $quickActions
        ]);
    }

    /**
     * Show recent activities
     * 
     * @return \Illuminate\View\View
     */
    public function recentActivities()
    {
        // Ensure we're getting a paginator instance
        $activities = Activity::with('causer')
            ->latest()
            ->paginate(20);

        // Get users for the filter dropdown
        $users = \App\Models\User::select('id', 'name')
            ->orderBy('name')
            ->get();

        // Ensure we're passing a paginator to the view
        return view('dashboard.recent-activities', [
            'activities' => $activities,
            'users' => $users
        ]);
    }

    /**
     * Alias for overview for backward compatibility
     */
    public function index()
    {
        return $this->overview();
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
