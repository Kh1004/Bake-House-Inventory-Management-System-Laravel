@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold mb-6">Demand Prediction</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label for="product_id" class="block text-sm font-medium text-gray-700">Select Product</label>
                <select id="product_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="prediction_method" class="block text-sm font-medium text-gray-700">Prediction Method</label>
                <select id="prediction_method" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    <option value="moving_average">7-Day Moving Average</option>
                    <option value="linear_regression">Linear Regression</option>
                    <option value="arima">ARIMA (Advanced)</option>
                </select>
            </div>
            <div>
                <label for="prediction_period" class="block text-sm font-medium text-gray-700">Prediction Period</label>
                <select id="prediction_period" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    <option value="7">Next Week (7 days)</option>
                    <option value="14">Next 2 Weeks (14 days)</option>
                    <option value="30">Next Month (30 days)</option>
                    <option value="60">Next 2 Months (60 days)</option>
                    <option value="90">Next 3 Months (90 days)</option>
                </select>
            </div>
        </div>

        <div class="bg-white p-4 md:p-6 rounded-lg shadow">
            <div class="flex flex-col space-y-4">
                <div class="flex justify-between items-center">
                    <h2 id="chartTitle" class="text-lg md:text-xl font-semibold text-gray-900">Demand Prediction</h2>
                    <div class="flex items-center space-x-2">
                        <span class="inline-block w-3 h-3 rounded-full bg-indigo-500"></span>
                        <span class="text-xs text-gray-600">Historical</span>
                        <span class="inline-block w-3 h-3 rounded-full bg-pink-500 ml-2"></span>
                        <span class="text-xs text-gray-600">Moving Avg</span>
                        <span class="inline-block w-3 h-3 rounded-full bg-emerald-500 ml-2"></span>
                        <span class="text-xs text-gray-600">Prediction</span>
                    </div>
                </div>
                <div class="relative" style="height: 400px;">
                    <div id="predictionError" class="hidden absolute inset-0 flex items-center justify-center bg-red-50 text-red-700 text-sm rounded">
                        <!-- Error message will appear here -->
                    </div>
                    <canvas id="demandChart"></canvas>
                </div>
            </div>
        </div>

        <div class="mt-8">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Prediction Summary</h3>
            <div class="bg-gray-50 p-4 rounded-lg">
                <div id="predictionSummary" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Summary will be populated by JavaScript -->
                </div>
            </div>
        </div>

        <!-- Feedback Form -->
        <div class="mt-8 bg-white p-6 rounded-lg shadow" id="feedbackSection">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Provide Feedback</h3>
            <form id="predictionFeedbackForm">
                @csrf
                <input type="hidden" name="product_id" id="feedbackProductId">
                <input type="hidden" name="prediction_method" id="feedbackMethod">
                <input type="hidden" name="prediction_date" id="feedbackDate">
                <input type="hidden" name="prediction_data" id="feedbackData">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="accuracyRating" class="block text-sm font-medium text-gray-700">Accuracy Rating (1-5)</label>
                        <select name="accuracy_rating" id="accuracyRating" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="">Select rating...</option>
                            <option value="1">1 - Very Inaccurate</option>
                            <option value="2">2 - Somewhat Inaccurate</option>
                            <option value="3">3 - Neutral</option>
                            <option value="4">4 - Somewhat Accurate</option>
                            <option value="5">5 - Very Accurate</option>
                        </select>
                    </div>
                    <div>
                        <label for="actualSales" class="block text-sm font-medium text-gray-700">Actual Sales (optional)</label>
                        <input type="number" name="actual_sales" id="actualSales" min="0" step="1" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                </div>
                
                <div class="mt-4">
                    <label for="userNotes" class="block text-sm font-medium text-gray-700">Additional Notes</label>
                    <textarea name="user_notes" id="userNotes" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                </div>
                
                <div class="mt-4">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Submit Feedback
                    </button>
                    <div id="feedbackSuccess" class="hidden mt-2 text-sm text-green-600">
                        Thank you for your feedback! It will help improve our predictions.
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Accuracy Statistics -->
        <div class="mt-8 bg-white p-6 rounded-lg shadow" id="accuracyStats">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Prediction Accuracy</h3>
            <div id="accuracyStatsContent" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="text-center p-4 bg-gray-50 rounded-lg" data-method="moving_average">
                    <div class="text-2xl font-bold text-indigo-600">-</div>
                    <div class="text-sm text-gray-500">7-Day Moving Average</div>
                    <div class="text-xs text-gray-400">Accuracy: -</div>
                </div>
                <div class="text-center p-4 bg-gray-50 rounded-lg" data-method="linear_regression">
                    <div class="text-2xl font-bold text-indigo-600">-</div>
                    <div class="text-sm text-gray-500">Linear Regression</div>
                    <div class="text-xs text-gray-400">Accuracy: -</div>
                </div>
                <div class="text-center p-4 bg-gray-50 rounded-lg" data-method="arima">
                    <div class="text-2xl font-bold text-indigo-600">-</div>
                    <div class="text-sm text-gray-500">ARIMA</div>
                    <div class="text-xs text-gray-400">Accuracy: -</div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/date-fns@2.29.3/format/index.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
<script>
    let demandChart;

    function loadPredictionData(productId) {
        console.log('Loading prediction for product ID:', productId);
        if (!productId) {
            console.error('No product ID provided');
            return;
        }
        
        const method = document.getElementById('prediction_method').value;
        const period = document.getElementById('prediction_period').value;
        const url = new URL(`/demand-prediction/api/predict-demand/${productId}`, window.location.origin);
        url.searchParams.append('method', method);
        url.searchParams.append('days_ahead', period);
        if (method === 'arima') {
            url.searchParams.append('fallback', 'false');
        }
        
        console.log('API URL:', url.toString());
        
        // Reset and hide previous error, show loading state
        const errorBox = document.getElementById('predictionError');
        if (errorBox) {
            errorBox.classList.add('hidden');
            errorBox.textContent = '';
        }
        const chartCanvas = document.getElementById('demandChart');
        const ctx = chartCanvas.getContext('2d');
        ctx.textAlign = 'center';
        ctx.fillText('Loading predictions...', chartCanvas.width / 2, chartCanvas.height / 2);
        
        fetch(url)
            .then(async (response) => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    let message = `Request failed (status ${response.status})`;
                    try {
                        const text = await response.text();
                        try {
                            const json = JSON.parse(text);
                            if (json && json.message) message = json.message;
                            else if (json && json.error) message = json.error;
                            else if (text) message = text;
                        } catch (_) {
                            if (text) message = text;
                        }
                    } catch (_) {}
                    throw new Error(message);
                }
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    updateChart(data.data, data.method);
                    updateSummary(data.data);
                } else {
                    console.error('API returned success:false', data);
                    const errorBox = document.getElementById('predictionError');
                    if (errorBox) {
                        errorBox.textContent = data.message || 'Failed to load prediction.';
                        errorBox.classList.remove('hidden');
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                const errorMsg = error.message || 'Failed to load prediction. Please try again.';
                const errorBox = document.getElementById('predictionError');
                if (errorBox) {
                    errorBox.textContent = errorMsg;
                    errorBox.classList.remove('hidden');
                }
            });
    }

    function updateChart(data, method) {
        document.getElementById('chartTitle').textContent = `Demand Prediction (${method})`;
        
        // Store current prediction data for feedback
        currentPredictionData = data;
        
        // Set the feedback form product ID
        document.getElementById('feedbackProductId').value = document.getElementById('product_id').value;
        document.getElementById('feedbackMethod').value = method;
        document.getElementById('feedbackDate').value = new Date().toISOString().split('T')[0];
        
        const canvas = document.getElementById('demandChart');
        const ctx = canvas.getContext('2d');
        
        // Store the device pixel ratio
        const devicePixelRatio = window.devicePixelRatio || 1;
        
        // Set the canvas size to match its display size multiplied by the device pixel ratio
        function resizeCanvas() {
            const container = canvas.parentElement;
            const width = container.clientWidth;
            const height = container.clientHeight;
            
            if (canvas.width !== width || canvas.height !== height) {
                canvas.width = width * devicePixelRatio;
                canvas.height = height * devicePixelRatio;
                canvas.style.width = width + 'px';
                canvas.style.height = height + 'px';
                ctx.scale(devicePixelRatio, devicePixelRatio);
                return true;
            }
            return false;
        }
        
        // Initial resize
        resizeCanvas();
        
        // Prepare datasets
        const labels = [...Object.keys(data.historical), ...Object.keys(data.predictions)];
        const historicalData = Object.values(data.historical);
        const movingAverages = Object.values(data.moving_averages);
        const predictions = [...Array(Object.keys(data.historical).length).fill(null), ...Object.values(data.predictions)];

        if (demandChart) {
            demandChart.destroy();
        }

        demandChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Historical Sales',
                        data: historicalData,
                        borderColor: 'rgb(99, 102, 241)',
                        backgroundColor: 'rgba(99, 102, 241, 0.1)',
                        tension: 0.3,
                        fill: true,
                        borderWidth: 2,
                        pointRadius: 3,
                        pointHoverRadius: 5
                    },
                    {
                        label: 'Moving Average',
                        data: movingAverages,
                        borderColor: 'rgb(236, 72, 153)',
                        borderDash: [5, 5],
                        borderWidth: 2,
                        pointRadius: 0,
                        pointHoverRadius: 0
                    },
                    {
                        label: 'Prediction',
                        data: predictions,
                        borderColor: 'rgb(16, 185, 129)',
                        borderWidth: 2,
                        borderDash: [3, 3],
                        pointRadius: 0,
                        pointHoverRadius: 5
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Quantity Sold',
                            font: {
                                weight: 'bold'
                            }
                        },
                        grid: {
                            drawBorder: false
                        }
                    },
                    x: {
                        type: 'time',
                        time: {
                            unit: 'day',
                            displayFormats: {
                                day: 'MMM d'  // This will show dates like "Jun 27"
                            },
                            tooltipFormat: 'EEE, MMM d, yyyy'  // This will show full date in tooltip
                        },
                        title: {
                            display: true,
                            text: 'Date'
                        },
                        grid: {
                            display: false
                        },
                        ticks: {
                            maxRotation: 45,
                            minRotation: 45
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleFont: { size: 14, weight: 'bold' },
                        bodyFont: { size: 13 },
                        padding: 12,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += Math.round(context.parsed.y * 100) / 100;
                                }
                                return label;
                            },
                            title: function(tooltipItems) {
                                // Format date as 'Weekday, MMM D, YYYY' (e.g., 'Thursday, Jun 27, 2024')
                                const date = new Date(tooltipItems[0].label);
                                return date.toLocaleDateString('en-US', { 
                                    weekday: 'long', 
                                    year: 'numeric', 
                                    month: 'short', 
                                    day: 'numeric' 
                                });
                            }
                        }
                    },
                    legend: {
                        position: 'top',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            font: {
                                size: 13
                            }
                        }
                    },
                    title: {
                        display: true,
                        text: 'Demand Prediction',
                        font: {
                            size: 16,
                            weight: 'bold'
                        },
                        padding: {
                            bottom: 20
                        }
                    }
                },
                layout: {
                    padding: {
                        left: 10,
                        right: 10,
                        top: 10,
                        bottom: 10
                    }
                }
            }
        });
    }

    function updateSummary(data) {
        const totalHistorical = Object.values(data.historical).reduce((a, b) => a + b, 0);
        const avgDailySales = totalHistorical / Object.keys(data.historical).length;
        const predictedDemand = Object.values(data.predictions).reduce((a, b) => a + b, 0);
        
        const summaryHTML = `
            <div class="bg-blue-50 p-4 rounded-lg">
                <h3 class="font-medium text-blue-800">Historical Sales (30 Days)</h3>
                <p class="text-2xl font-bold text-blue-600">${totalHistorical}</p>
                <p class="text-sm text-gray-500">${avgDailySales.toFixed(2)} units/day on average</p>
            </div>
            <div class="bg-purple-50 p-4 rounded-lg">
                <h3 class="font-medium text-purple-800">Predicted Demand (Next 7 Days)</h3>
                <p class="text-2xl font-bold text-purple-600">${predictedDemand.toFixed(0)}</p>
                <p class="text-sm text-gray-500">${(predictedDemand/7).toFixed(2)} units/day on average</p>
            </div>
            <div class="bg-green-50 p-4 rounded-lg">
                <h3 class="font-medium text-green-800">Recommendation</h3>
                <p class="text-sm text-gray-700">${getRecommendation(avgDailySales, predictedDemand/7)}</p>
            </div>
        `;
        
        document.getElementById('predictionSummary').innerHTML = summaryHTML;
    }

    function getRecommendation(avgDaily, predictedDaily) {
        const diff = predictedDaily - avgDaily;
        const percentage = (Math.abs(diff) / avgDaily) * 100;
        
        if (diff > 0) {
            return `Increase inventory by ~${percentage.toFixed(0)}% (${Math.ceil(diff)} units/day)`;
        } else if (diff < 0) {
            return `Decrease inventory by ~${percentage.toFixed(0)}% (${Math.ceil(-diff)} units/day)`;
        } else {
            return 'Maintain current inventory levels';
        }
    }

    // Global variables
    let currentPredictionData = null;
    
    // Format number with commas
    function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
    
    // Load accuracy statistics
    function loadAccuracyStats(productId) {
        fetch(`/demand-prediction/api/feedback/accuracy-stats/${productId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateAccuracyStats(data.data);
                }
            })
            .catch(error => console.error('Error loading accuracy stats:', error));
    }
    
    // Update accuracy stats UI
    function updateAccuracyStats(stats) {
        const container = document.getElementById('accuracyStatsContent');
        if (!container) return;
        
        // Reset all stats
        const statBoxes = container.querySelectorAll('div[data-method]');
        statBoxes.forEach(box => {
            box.querySelector('div:first-child').textContent = '-';
            box.querySelector('div:last-child').textContent = 'Accuracy: -';
        });
        
        // Update with actual data
        stats.forEach(stat => {
            const method = stat.prediction_method;
            const box = container.querySelector(`div[data-method="${method}"]`);
            if (box) {
                const avgAccuracy = stat.avg_accuracy ? stat.avg_accuracy.toFixed(1) : 'N/A';
                box.querySelector('div:first-child').textContent = stat.total_predictions || '0';
                box.querySelector('div:last-child').textContent = `Accuracy: ${avgAccuracy}/5`;
            }
        });
    }
    
    // Initialize


    document.addEventListener('DOMContentLoaded', function() {
        const productSelect = document.getElementById('product_id');
        const methodSelect = document.getElementById('prediction_method');
        const feedbackForm = document.getElementById('predictionFeedbackForm');
        
        // Simple resize handler for the chart
        window.addEventListener('resize', function() {
            if (demandChart) {
                demandChart.resize();
            }
        });
        
        // Load initial data
        function loadAllData() {
            const productId = productSelect.value;
            loadPredictionData(productId);
            loadAccuracyStats(productId);
        }
        
        // Update on product or method change
        function updatePrediction() {
            const productId = productSelect.value;
            loadPredictionData(productId);
            loadAccuracyStats(productId);
            
            // Update feedback form with current product and method
            document.getElementById('feedbackProductId').value = productId;
            document.getElementById('feedbackMethod').value = methodSelect.value;
            document.getElementById('feedbackDate').value = new Date().toISOString().split('T')[0];
        }
        
        // Handle feedback form submission
        if (feedbackForm) {
            feedbackForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(feedbackForm);
                
                // Add current prediction data to form
                if (currentPredictionData) {
                    formData.set('prediction_data', JSON.stringify(currentPredictionData));
                }
                
                fetch('/demand-prediction/api/feedback', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(Object.fromEntries(formData))
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        const successMsg = document.getElementById('feedbackSuccess');
                        successMsg.classList.remove('hidden');
                        
                        // Reset form
                        feedbackForm.reset();
                        
                        // Hide success message after 5 seconds
                        setTimeout(() => {
                            successMsg.classList.add('hidden');
                        }, 5000);
                        
                        // Reload accuracy stats
                        loadAccuracyStats(productSelect.value);
                    }
                })
                .catch(error => {
                    console.error('Error submitting feedback:', error);
                    alert('Failed to submit feedback. Please try again.');
                });
            });
        }
        
        // Set up event listeners
        productSelect.addEventListener('change', updatePrediction);
        methodSelect.addEventListener('change', updatePrediction);
        document.getElementById('prediction_period').addEventListener('change', updatePrediction);
        
        // Initial load
        updatePrediction();
        loadAccuracyStats(productSelect.value);
        
        // Set up feedback form with initial values
        document.getElementById('feedbackProductId').value = productSelect.value;
        document.getElementById('feedbackMethod').value = methodSelect.value;
        document.getElementById('feedbackDate').value = new Date().toISOString().split('T')[0];
    });
</script>
@endpush

@endsection