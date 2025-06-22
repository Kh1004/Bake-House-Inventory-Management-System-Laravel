<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use App\Models\Ingredient;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function sales(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));
        $groupBy = $request->input('group_by', 'day'); // day, week, month, year

        $query = Sale::query()
            ->select(
                DB::raw('DATE(sale_date) as date'),
                DB::raw('COUNT(*) as total_sales'),
                DB::raw('SUM(total_amount) as total_revenue'),
                DB::raw('SUM(CASE WHEN payment_status = "paid" THEN total_amount ELSE 0 END) as paid_amount'),
                DB::raw('COUNT(CASE WHEN payment_status = "paid" THEN 1 END) as paid_count')
            )
            ->whereBetween('sale_date', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date');

        $salesData = $query->get();

        // Format data for charts
        $labels = [];
        $revenueData = [];
        $salesCountData = [];

        foreach ($salesData as $sale) {
            $labels[] = Carbon::parse($sale->date)->format('M d');
            $revenueData[] = $sale->total_revenue;
            $salesCountData[] = $sale->total_sales;
        }

        // Top selling products
        $topProducts = Product::withCount(['sales as total_quantity' => function($query) use ($startDate, $endDate) {
                $query->select(DB::raw('COALESCE(SUM(sale_items.quantity), 0)'))
                    ->join('sale_items', 'sale_items.product_id', '=', 'products.id')
                    ->join('sales', 'sales.id', '=', 'sale_items.sale_id')
                    ->whereBetween('sales.sale_date', [$startDate, $endDate]);
            }])
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->get();

        return view('reports.sales', [
            'salesData' => $salesData,
            'topProducts' => $topProducts,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'groupBy' => $groupBy,
            'chartData' => [
                'labels' => $labels,
                'revenue' => $revenueData,
                'salesCount' => $salesCountData,
            ]
        ]);
    }

    public function inventory(Request $request)
    {
        $sortBy = $request->input('sort_by', 'current_stock');
        $sortOrder = $request->input('sort_order', 'asc');
        
        $ingredients = Ingredient::with(['category', 'unit'])
            ->withSum(['inventoryLogs as total_in' => function($query) {
                $query->where('type', 'purchase')
                    ->select(DB::raw('COALESCE(SUM(quantity), 0)'));
            }])
            ->withSum(['inventoryLogs as total_out' => function($query) {
                $query->whereIn('type', ['sale', 'waste'])
                    ->select(DB::raw('COALESCE(SUM(quantity), 0)'));
            }])
            ->orderBy($sortBy, $sortOrder)
            ->paginate(20);

        // Low stock items
        $lowStockItems = Ingredient::whereColumn('current_stock', '<=', 'minimum_stock')
            ->orderBy('current_stock')
            ->get();

        // Stock value
        $totalStockValue = Ingredient::sum(DB::raw('current_stock * unit_price'));

        return view('reports.inventory', [
            'ingredients' => $ingredients,
            'lowStockItems' => $lowStockItems,
            'totalStockValue' => $totalStockValue,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
        ]);
    }
}
