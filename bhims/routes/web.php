<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LowStockIngredientController;
use App\Http\Controllers\Api\DemandPredictionController as ApiDemandPredictionController;
use App\Http\Controllers\DemandPredictionController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Home Route - Show dashboard
Route::get('/', [\App\Http\Controllers\DashboardController::class, 'overview'])
    ->middleware(['auth', 'verified'])
    ->name('home');

// Debug routes
Route::get('/debug/alerts', function() {
    if (!auth()->check()) {
        return 'Not authenticated';
    }
    
    $alerts = \App\Models\Alert::where('user_id', auth()->id())
        ->orderBy('created_at', 'desc')
        ->get();
        
    return response()->json([
        'user_id' => auth()->id(),
        'alerts' => $alerts
    ]);
});

Route::get('/debug/low-stock-items', function() {
    $ingredients = \App\Models\Ingredient::whereColumn('current_stock', '<', 'minimum_stock')
        ->get(['id', 'name', 'current_stock', 'minimum_stock', 'unit']);
        
    return response()->json([
        'low_stock_items' => $ingredients
    ]);
});

// Test low-stock route (temporarily outside auth)
Route::get('ingredients/low-stock', [IngredientController::class, 'lowStock'])
    ->name('ingredients.low-stock.test');

// Dashboard Routes
Route::prefix('dashboard')->name('dashboard.')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [\App\Http\Controllers\DashboardController::class, 'overview'])->name('overview');
    Route::get('/quick-actions', [\App\Http\Controllers\DashboardController::class, 'quickActions'])->name('quick-actions');
    Route::get('/recent-activities', [\App\Http\Controllers\DashboardController::class, 'recentActivities'])->name('recent-activities');
});

// Legacy dashboard route for backward compatibility
Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'overview'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Authentication Routes
require __DIR__.'/auth.php';

// Roles & Permissions Routes
Route::middleware(['auth'])->group(function () {
    // Roles
    Route::resource('roles', \App\Http\Controllers\RoleController::class);
    
    // Permissions
    Route::resource('permissions', \App\Http\Controllers\PermissionController::class);
});

// Alert Settings Routes
Route::prefix('settings')->name('settings.')->middleware(['auth'])->group(function () {
    Route::resource('alerts', \App\Http\Controllers\Settings\AlertSettingsController::class)->names('alerts');
    
    // Explicitly define the index route to ensure it's available
    Route::get('/alerts', [\App\Http\Controllers\Settings\AlertSettingsController::class, 'index'])
        ->name('alerts.index');
});

// Alert Configuration Routes
require __DIR__.'/alert-configurations.php';

// Test routes (remove in production)
if (app()->environment('local')) {
    require __DIR__.'/test-activity.php';
}

// User Management Routes
Route::middleware(['auth'])->group(function () {
    Route::resource('users', \App\Http\Controllers\UserController::class)->except(['show']);
    Route::put('users/{user}/change-password', [\App\Http\Controllers\UserController::class, 'changePassword'])->name('users.change-password');
    Route::put('users/{user}/toggle-status', [\App\Http\Controllers\UserController::class, 'toggleStatus'])->name('users.toggle-status');
});

// Authenticated Routes
Route::middleware(['auth'])->group(function () {
    // Alerts routes
    Route::resource('alerts', \App\Http\Controllers\AlertController::class)->only(['index', 'destroy']);
    
    // Test authenticated route
    Route::get('/test-authenticated', function() {
        return 'Authenticated test route works!';
    });
    
    // Temporary debug route
    Route::get('/debug-alerts', function() {
        $alerts = \App\Models\Alert::all();
        $unreadCount = \App\Models\Alert::where('is_read', false)->count();
        $userAlerts = \App\Models\Alert::where('user_id', auth()->id())->get();
        
        return [
            'all_alerts' => $alerts,
            'unread_count' => $unreadCount,
            'user_alerts' => $userAlerts,
            'current_user_id' => auth()->id(),
            'total_alerts' => $alerts->count(),
        ];
    })->middleware('auth');
    
    // Test route to display alert configurations
    Route::get('/test-alerts', function() {
        $alerts = \App\Models\Alert::where('user_id', auth()->id())
            ->latest()
            ->paginate(10); // Show 10 alerts per page
        return view('test.alerts', ['alerts' => $alerts]);
    })->name('test-alerts');
    
    // Alert routes
    Route::resource('alerts', \App\Http\Controllers\AlertController::class)->only(['destroy']);

    // Test direct route
    Route::get('/test-market-route', function() {
        return 'Test market route works!';
    });

    // Market Prediction Dashboard Web Route
    Route::get('market-prediction', [\App\Http\Controllers\MarketPredictionDashboardController::class, 'index'])
        ->name('market-prediction.index');
    
    // Market Prediction API Routes
    Route::prefix('api/market-predictions')->name('market-predictions.')->group(function () {
        // Get dashboard data
        Route::get('/dashboard', [\App\Http\Controllers\MarketPredictionDashboardController::class, 'getDashboardData'])
            ->name('dashboard');
            
        // Get demand forecast
        Route::get('/products/{product}/forecast', [\App\Http\Controllers\MarketPredictionController::class, 'getDemandForecast'])
            ->name('forecast');
            
        // Get sales trends
        Route::get('/products/{product}/trends', [\App\Http\Controllers\MarketPredictionController::class, 'getSalesTrends'])
            ->name('trends');
            
        // Get inventory recommendations
        Route::get('/products/{product}/recommendations', [\App\Http\Controllers\MarketPredictionController::class, 'getInventoryRecommendations'])
            ->name('recommendations');
    });

    // User Management Routes
    Route::resource('users', \App\Http\Controllers\UserController::class)->except(['show']);
    
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/change-password', [ProfileController::class, 'showChangePasswordForm'])->name('profile.password');
    Route::post('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.password.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Ingredients Routes
    Route::resource('ingredients', IngredientController::class);
    Route::get('ingredients/low-stock', [IngredientController::class, 'lowStock'])
        ->name('ingredients.low-stock');
    Route::get('ingredients/{ingredient}/adjust-stock', [IngredientController::class, 'showAdjustStock'])
        ->name('ingredients.adjust-stock');
    Route::post('ingredients/{ingredient}/adjust-stock', [IngredientController::class, 'adjustStock']);

    // Categories Routes
    Route::resource('categories', CategoryController::class);
    
    // Products Routes
    Route::resource('products', \App\Http\Controllers\ProductController::class);
    
    // Suppliers Routes
    Route::resource('suppliers', \App\Http\Controllers\SupplierController::class);
    
    // Sales Routes
    Route::resource('sales', \App\Http\Controllers\SaleController::class);
    Route::get('sales/{sale}/print', [\App\Http\Controllers\SaleController::class, 'print'])->name('sales.print');
    Route::post('sales/{sale}/send-receipt', [\App\Http\Controllers\SaleController::class, 'sendReceipt'])->name('sales.email');
    
    // Customers Routes
    Route::resource('customers', \App\Http\Controllers\CustomerController::class);
    
    // Settings Routes - Using AlertSettingsController for all alert settings

    // Reports Routes
    Route::prefix('reports')->group(function () {
        Route::get('/sales', [\App\Http\Controllers\ReportController::class, 'sales'])->name('reports.sales');
        Route::get('/inventory', [\App\Http\Controllers\ReportController::class, 'inventory'])->name('reports.inventory');
    });

    // Profile Routes
    Route::middleware(['auth'])->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    // Competitor Analysis Routes
    Route::prefix('competitor-analysis')->name('competitor-analysis.')->group(function () {
        Route::get('/', [\App\Http\Controllers\CompetitorAnalysisController::class, 'index'])->name('index');
        Route::get('create', [\App\Http\Controllers\CompetitorAnalysisController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\CompetitorAnalysisController::class, 'store'])->name('store');
        Route::get('edit/{analysis}', [\App\Http\Controllers\CompetitorAnalysisController::class, 'edit'])->name('edit');
        Route::put('update/{analysis}', [\App\Http\Controllers\CompetitorAnalysisController::class, 'update'])->name('update');
        Route::delete('delete/{analysis}', [\App\Http\Controllers\CompetitorAnalysisController::class, 'destroy'])->name('delete');
        Route::get('dashboard', [\App\Http\Controllers\CompetitorAnalysisController::class, 'dashboard'])->name('dashboard');
    });

    // Recipes Routes
    Route::resource('recipes', \App\Http\Controllers\RecipeController::class);
    Route::put('recipes/{recipe}/toggle-status', [\App\Http\Controllers\RecipeController::class, 'toggleStatus'])
        ->name('recipes.toggle-status');
        
    // Purchase Orders Routes
    Route::resource('purchase-orders', \App\Http\Controllers\PurchaseOrderController::class)->except(['edit']);
    Route::post('purchase-orders/{purchaseOrder}/update-status', [\App\Http\Controllers\PurchaseOrderController::class, 'updateStatus'])
        ->name('purchase-orders.update-status');
});

// Demand Prediction Routes
Route::middleware(['auth'])->prefix('demand-prediction')->group(function () {
    Route::get('/', [DemandPredictionController::class, 'index'])->name('demand-prediction.index');
    
    // API Routes
    Route::prefix('api')->group(function () {
        // Prediction endpoints
        Route::get('/predict-demand/{productId}', [ApiDemandPredictionController::class, 'getProductPrediction'])
            ->name('api.predict.demand');
            
        // Feedback endpoints
        Route::middleware('auth')->group(function () {
            Route::post('/feedback', [\App\Http\Controllers\Api\PredictionFeedbackController::class, 'store'])
                ->name('api.feedback.store');
            Route::get('/feedback/product/{productId}', [\App\Http\Controllers\Api\PredictionFeedbackController::class, 'getProductFeedback'])
                ->name('api.feedback.product');
            Route::get('/feedback/accuracy-stats', [\App\Http\Controllers\Api\PredictionFeedbackController::class, 'getAccuracyStats'])
                ->name('api.feedback.stats');
            Route::get('/feedback/accuracy-stats/{productId}', [\App\Http\Controllers\Api\PredictionFeedbackController::class, 'getAccuracyStats'])
                ->name('api.feedback.stats.product');
        });
    });
});

Route::get('/debug/ingredients-table', function () {
    try {
        $columns = DB::select('DESCRIBE ingredients');
        return response()->json([
            'status' => 'success',
            'table' => 'ingredients',
            'columns' => array_column($columns, 'Field')
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
});