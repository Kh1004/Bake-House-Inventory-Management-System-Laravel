<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Models\CompetitorAnalysis;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CompetitorAnalysisController extends Controller
{
    public function index()
    {
        return view('competitor-analysis.index');
    }

    public function create()
    {
        return view('competitor-analysis.create', [
            'currency' => 'LKR' // Default currency
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'competitor_name' => 'required|string|max:255',
            'product_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'location' => 'nullable|string|max:255',
            'analysis_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        CompetitorAnalysis::create($validated);

        return redirect()->route('competitor-analysis.dashboard')
            ->with('success', 'Competitor analysis has been added successfully.');
    }

    public function edit(CompetitorAnalysis $analysis)
    {
        return view('competitor-analysis.edit', [
            'analysis' => $analysis,
            'currency' => 'LKR' // Default currency
        ]);
    }

    public function update(Request $request, CompetitorAnalysis $analysis)
    {
        $validated = $request->validate([
            'competitor_name' => 'required|string|max:255',
            'product_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'location' => 'nullable|string|max:255',
            'analysis_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $analysis->update($validated);

        return redirect()->route('competitor-analysis.dashboard')
            ->with('success', 'Competitor analysis has been updated successfully.');
    }

    public function destroy(CompetitorAnalysis $analysis)
    {
        $analysis->delete();

        return redirect()->route('competitor-analysis.dashboard')
            ->with('success', 'Competitor analysis has been deleted successfully.');
    }

    public function dashboard(Request $request)
    {
        // Get filters from request
        $competitor = $request->query('competitor', '');
        $product = $request->query('product', '');
        $dateRange = $request->query('date_range', '1');

        // Calculate date range based on selection
        $endDate = now();
        switch ($dateRange) {
            case '1': // Last 30 days
                $startDate = $endDate->copy()->subDays(30);
                break;
            case '2': // Last 90 days
                $startDate = $endDate->copy()->subDays(90);
                break;
            case '3': // Last 180 days
                $startDate = $endDate->copy()->subDays(180);
                break;
            default: // Custom range
                $startDate = $endDate->copy()->subDays(30);
        }

        // Base query
        $query = CompetitorAnalysis::whereBetween('analysis_date', [$startDate, $endDate]);

        // Apply filters
        if ($competitor) {
            $query->where('competitor_name', $competitor);
        }
        if ($product) {
            $query->where('product_name', $product);
        }

        // Get analyses
        $analyses = $query->orderBy('analysis_date', 'desc')->paginate(15);

        // Get unique competitors and products
        $competitors = CompetitorAnalysis::select('competitor_name')
            ->distinct()
            ->pluck('competitor_name')
            ->toArray();

        $products = CompetitorAnalysis::select('product_name')
            ->distinct()
            ->pluck('product_name')
            ->toArray();

        // Calculate statistics
        $totalCompetitors = count($competitors);
        $totalProducts = count($products);

        // Calculate average price change
        $avgPriceChange = 0;
        $totalChanges = 0;
        $previousPrices = [];

        foreach ($analyses as $analysis) {
            $key = $analysis->competitor_name . '_' . $analysis->product_name;
            if (isset($previousPrices[$key])) {
                $previousPrice = $previousPrices[$key];
                $currentPrice = $analysis->price;
                $priceChange = (($currentPrice - $previousPrice) / $previousPrice) * 100;
                $avgPriceChange += $priceChange;
                $totalChanges++;
            }
            $previousPrices[$key] = $analysis->price;
        }

        $avgPriceChange = $totalChanges > 0 ? round($avgPriceChange / $totalChanges, 2) : 0;

        // Prepare data for price comparison chart
        $yourPrices = [];
        $competitorPrices = [];
        $dateLabels = [];

        // This is a simplified example - in a real application, you would compare with your own prices
        foreach ($analyses as $analysis) {
            $dateLabels[] = Carbon::parse($analysis->analysis_date)->format('Y-m-d');
            $competitorPrices[] = floatval($analysis->price);
            // Add your own prices here
            $yourPrices[] = 0; // Replace with actual prices
        }

        return view('competitor-analysis.dashboard', compact(
            'analyses',
            'competitors',
            'products',
            'totalCompetitors',
            'totalProducts',
            'avgPriceChange',
            'yourPrices',
            'competitorPrices',
            'dateLabels'
        ));
    }
}
