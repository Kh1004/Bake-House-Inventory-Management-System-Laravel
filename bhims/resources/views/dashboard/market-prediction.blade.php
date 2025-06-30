@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header and Product Selection -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
        <div class="mb-4 md:mb-0">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Market Prediction Dashboard</h1>
            <p class="text-gray-600">Demand forecasting and inventory optimization</p>
        </div>
        
        <div class="w-full md:w-1/3">
            <label for="product-select" class="block text-sm font-medium text-gray-700 mb-1">Select Product</label>
            <div class="relative">
                <select id="product-select" class="block w-full bg-white border border-gray-300 text-gray-700 py-2 px-4 pr-8 rounded leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" {{ $selectedProduct && $selectedProduct->id === $product->id ? 'selected' : '' }}>
                            {{ $product->name }}
                        </option>
                    @endforeach
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Indicator -->
    <div id="loading-indicator" class="text-center py-8 hidden">
        <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500 mx-auto"></div>
        <p class="mt-2 text-gray-600">Loading data...</p>
    </div>

    <!-- Dashboard Content -->
    <div id="dashboard-content" class="space-y-8">
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Current Stock -->
            <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-blue-500">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Current Stock</p>
                        <p id="current-stock" class="text-2xl font-semibold text-gray-800">--</p>
                    </div>
                </div>
            </div>

            <!-- Predicted Demand -->
            <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-green-500">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Predicted Demand (30 days)</p>
                        <p id="predicted-demand" class="text-2xl font-semibold text-gray-800">--</p>
                    </div>
                </div>
            </div>

            <!-- Recommendation -->
            <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-yellow-500">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Recommendation</p>
                        <p id="recommendation" class="text-lg font-semibold text-gray-800">--</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Demand Forecast Chart -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Demand Forecast</h3>
                <div class="h-64">
                    <canvas id="demand-forecast-chart"></canvas>
                </div>
            </div>

            <!-- Sales Trends Chart -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Sales Trends (Last 90 Days)</h3>
                <div class="h-64">
                    <canvas id="sales-trends-chart"></canvas>
                </div>
            </div>
        </div>

        <!-- Detailed Recommendations -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Inventory Recommendations</h3>
            <div id="inventory-recommendations" class="space-y-4">
                <p class="text-gray-600">Select a product to view recommendations.</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Global chart instances
    let demandForecastChart = null;
    let salesTrendsChart = null;

    // Initialize the dashboard when the page loads
    document.addEventListener('DOMContentLoaded', function() {
        const productSelect = document.getElementById('product-select');
        
        // Load initial data for the first product
        if (productSelect.value) {
            loadDashboardData(productSelect.value);
        }
        
        // Add event listener for product selection change
        productSelect.addEventListener('change', function() {
            loadDashboardData(this.value);
        });
    });

    // Function to load dashboard data
    function loadDashboardData(productId) {
        const loadingIndicator = document.getElementById('loading-indicator');
        const dashboardContent = document.getElementById('dashboard-content');
        
        // Show loading indicator
        loadingIndicator.classList.remove('hidden');
        dashboardContent.classList.add('opacity-50', 'pointer-events-none');
        
        // Fetch data from the server
        fetch(`/api/market-predictions/dashboard?product_id=${productId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateDashboard(data);
                } else {
                    console.error('Error loading dashboard data:', data.message);
                    alert('Failed to load dashboard data. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while loading the dashboard. Please try again.');
            })
            .finally(() => {
                // Hide loading indicator
                loadingIndicator.classList.add('hidden');
                dashboardContent.classList.remove('opacity-50', 'pointer-events-none');
            });
    }

    // Function to update the dashboard with new data
    function updateDashboard(data) {
        // Update summary cards
        document.getElementById('current-stock').textContent = data.product.current_stock || '--';
        
        // Calculate total predicted demand
        const totalDemand = data.forecast.predictions.reduce((sum, pred) => sum + pred.predicted_demand, 0);
        document.getElementById('predicted-demand').textContent = Math.round(totalDemand);
        
        // Update recommendation
        const recElement = document.getElementById('recommendation');
        if (data.recommendations?.success) {
            const rec = data.recommendations.recommendations;
            recElement.textContent = rec.message;
            recElement.className = 'text-lg font-semibold ' + 
                (rec.status === 'reorder' ? 'text-red-600' : 
                 rec.status === 'monitor' ? 'text-yellow-600' : 'text-green-600');
        } else {
            recElement.textContent = 'No recommendation available';
            recElement.className = 'text-lg font-semibold text-gray-600';
        }
        
        // Update charts
        updateDemandForecastChart(data.forecast);
        updateSalesTrendsChart(data.trends);
        
        // Update inventory recommendations
        updateInventoryRecommendations(data.recommendations);
    }

    // Function to update the demand forecast chart
    function updateDemandForecastChart(forecastData) {
        const ctx = document.getElementById('demand-forecast-chart').getContext('2d');
        
        // Destroy existing chart if it exists
        if (demandForecastChart) {
            demandForecastChart.destroy();
        }
        
        if (!forecastData.success || !forecastData.predictions || forecastData.predictions.length === 0) {
            console.log('No forecast data available');
            return;
        }
        
        // Prepare data for the chart
        const labels = forecastData.predictions.map(pred => {
            const date = new Date(pred.date);
            return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
        });
        
        const demandData = forecastData.predictions.map(pred => pred.predicted_demand);
        const lowerBounds = forecastData.predictions.map(pred => pred.confidence_lower);
        const upperBounds = forecastData.predictions.map(pred => pred.confidence_upper);
        
        // Create the chart
        demandForecastChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Predicted Demand',
                        data: demandData,
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.3
                    },
                    {
                        label: 'Confidence Interval',
                        data: upperBounds.map((upper, i) => upper - lowerBounds[i]),
                        backgroundColor: 'rgba(59, 130, 246, 0.2)',
                        borderWidth: 0,
                        fill: 1,
                        tension: 0.3
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Quantity'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Date'
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label === 'Confidence Interval') {
                                    const lower = lowerBounds[context.dataIndex];
                                    const upper = upperBounds[context.dataIndex];
                                    return `${label}: ${lower.toFixed(0)} - ${upper.toFixed(0)}`;
                                }
                                return `${label}: ${context.raw}`;
                            }
                        }
                    }
                }
            }
        });
    }

    // Function to update the sales trends chart
    function updateSalesTrendsChart(trendsData) {
        const ctx = document.getElementById('sales-trends-chart').getContext('2d');
        
        // Destroy existing chart if it exists
        if (salesTrendsChart) {
            salesTrendsChart.destroy();
        }
        
        if (!trendsData.success || !trendsData.daily_trends || trendsData.daily_trends.length === 0) {
            console.log('No trends data available');
            return;
        }
        
        // Prepare data for the chart (show last 30 days)
        const last30Days = trendsData.daily_trends.slice(-30);
        const labels = last30Days.map(day => {
            const date = new Date(day.date);
            return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
        });
        
        const salesData = last30Days.map(day => day.quantity_sold);
        
        // Create the chart
        salesTrendsChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Quantity Sold',
                    data: salesData,
                    backgroundColor: 'rgba(16, 185, 129, 0.7)',
                    borderColor: 'rgb(16, 185, 129)',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Quantity Sold'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Date'
                        },
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    }

    // Function to update inventory recommendations
    function updateInventoryRecommendations(recommendations) {
        const container = document.getElementById('inventory-recommendations');
        
        if (!recommendations?.success) {
            container.innerHTML = '<p class="text-gray-600">No recommendations available for the selected product.</p>';
            return;
        }
        
        const rec = recommendations.recommendations;
        const daysLeft = Math.round(rec.days_of_stock_left);
        
        let statusBadge = '';
        if (rec.status === 'reorder') {
            statusBadge = '<span class="px-2 py-1 text-xs font-semibold text-white bg-red-500 rounded-full">Action Required</span>';
        } else if (rec.status === 'monitor') {
            statusBadge = '<span class="px-2 py-1 text-xs font-semibold text-white bg-yellow-500 rounded-full">Monitor</span>';
        } else {
            statusBadge = '<span class="px-2 py-1 text-xs font-semibold text-white bg-green-500 rounded-full">Good</span>';
        }
        
        container.innerHTML = `
            <div class="bg-${rec.status === 'reorder' ? 'red' : rec.status === 'monitor' ? 'yellow' : 'green'}-50 p-4 rounded-lg">
                <div class="flex justify-between items-start">
                    <div>
                        <h4 class="text-lg font-semibold text-gray-800">${rec.message}</h4>
                        <p class="text-sm text-gray-600 mt-1">${daysLeft} days of stock remaining at current demand rate</p>
                    </div>
                    ${statusBadge}
                </div>
                
                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-white p-3 rounded-lg shadow-sm">
                        <p class="text-sm text-gray-500">Current Stock</p>
                        <p class="text-xl font-semibold">${rec.current_stock} units</p>
                    </div>
                    <div class="bg-white p-3 rounded-lg shadow-sm">
                        <p class="text-sm text-gray-500">Average Daily Demand</p>
                        <p class="text-xl font-semibold">${rec.average_daily_demand.toFixed(1)} units/day</p>
                    </div>
                    <div class="bg-white p-3 rounded-lg shadow-sm">
                        <p class="text-sm text-gray-500">Reorder Point</p>
                        <p class="text-xl font-semibold">${rec.reorder_point} units</p>
                    </div>
                    ${rec.suggested_order_quantity > 0 ? `
                    <div class="bg-blue-50 p-3 rounded-lg shadow-sm border-l-4 border-blue-500">
                        <p class="text-sm text-blue-700 font-medium">Suggested Order Quantity</p>
                        <p class="text-xl font-bold text-blue-800">${rec.suggested_order_quantity} units</p>
                    </div>
                    ` : ''}
                </div>
            </div>
        `;
    }
</script>
@endpush
@endsection