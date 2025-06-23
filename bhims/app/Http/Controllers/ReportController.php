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
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total_sales'),
                DB::raw('SUM(total) as total_revenue'),
                DB::raw('SUM(CASE WHEN payment_method IS NOT NULL THEN total ELSE 0 END) as paid_amount'),
                DB::raw('COUNT(CASE WHEN payment_method IS NOT NULL THEN 1 END) as paid_count')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
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
        $topProducts = Product::select([
                'products.id',
                'products.name',
                'products.sku',
                'products.selling_price',
                DB::raw('COALESCE(SUM(sale_products.quantity), 0) as total_quantity'),
                DB::raw('COALESCE(SUM(sale_products.quantity * sale_products.unit_price), 0) as total_revenue')
            ])
            ->leftJoin('sale_products', 'products.id', '=', 'sale_products.product_id')
            ->leftJoin('sales', function($join) use ($startDate, $endDate) {
                $join->on('sales.id', '=', 'sale_products.sale_id')
                    ->whereBetween('sales.created_at', [$startDate, $endDate])
                    ->whereNull('sales.deleted_at');
            })
            ->groupBy([
                'products.id',
                'products.name',
                'products.sku',
                'products.selling_price'
            ])
            ->orderBy('total_quantity', 'desc')
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
        
        $ingredients = Ingredient::with('category')
            ->withSum(['stockMovements as total_in' => function($query) {
                $query->where('movement_type', 'purchase');
            }], 'quantity')
            ->withSum(['stockMovements as total_out' => function($query) {
                $query->whereIn('movement_type', ['sale', 'waste']);
            }], 'quantity')
            ->withCasts([
                'total_in' => 'float',
                'total_out' => 'float'
            ])
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
