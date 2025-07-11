@extends('layouts.app')
@php
    use Carbon\Carbon;
@endphp

@section('content')
<script>
    function applyFilters() {
        const competitor = document.getElementById('competitor').value;
        const product = document.getElementById('product').value;
        const dateRange = document.getElementById('date_range').value;
        
        window.location.href = `{{ route('competitor-analysis.dashboard') }}?competitor=${competitor}&product=${product}&date_range=${dateRange}`;
    }

    // Maintain filter states
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const competitorValue = urlParams.get('competitor');
        const productValue = urlParams.get('product');
        const dateRangeValue = urlParams.get('date_range');

        if (competitorValue) {
            document.getElementById('competitor').value = competitorValue;
        }
        if (productValue) {
            document.getElementById('product').value = productValue;
        }
        if (dateRangeValue) {
            document.getElementById('date_range').value = dateRangeValue;
        }
    });
</script>
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold">Competitor Analysis Dashboard</h2>
                    <a href="{{ route('competitor-analysis.create') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Add New Analysis
                    </a>
                </div>

                <!-- Competitor Analysis Filters -->
                <div class="mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label for="competitor" class="block text-sm font-medium text-gray-700">Competitor</label>
                            <select id="competitor" name="competitor" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">All Competitors</option>
                                @foreach($competitors as $competitor)
                                    <option value="{{ $competitor }}">{{ $competitor }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="product" class="block text-sm font-medium text-gray-700">Product</label>
                            <select id="product" name="product" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">All Products</option>
                                @foreach($products as $product)
                                    <option value="{{ $product }}">{{ $product }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="date_range" class="block text-sm font-medium text-gray-700">Date Range</label>
                            <select id="date_range" name="date_range" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="1">Last 30 Days</option>
                                <option value="2">Last 90 Days</option>
                                <option value="3">Last 180 Days</option>
                                <option value="4">Custom Range</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="button" onclick="applyFilters()" 
                                    class="mt-1 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Apply Filters
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Competitor Analysis Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Total Competitors Tracked</dt>
                                        <dd class="text-lg font-semibold text-gray-900">{{ $totalCompetitors }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Total Products Analyzed</dt>
                                        <dd class="text-lg font-semibold text-gray-900">{{ $totalProducts }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Average Price Change</dt>
                                        <dd class="text-lg font-semibold text-gray-900">{{ $avgPriceChange }}%</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Price Comparison Chart -->
                <div class="mb-6">
                    <h3 class="text-lg font-medium mb-4">Price Comparison Over Time</h3>
                    <div id="priceComparisonChart" class="h-96"></div>
                </div>

                <!-- Competitor Analysis Table -->
                <div class="bg-white shadow rounded-lg">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Competitor</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Analysis Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($analyses as $analysis)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $analysis->competitor_name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $analysis->product_name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $analysis->price }} {{ $analysis->currency }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $analysis->location }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ Carbon::parse($analysis->analysis_date)->format('Y-m-d') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('competitor-analysis.edit', $analysis) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                            <form action="{{ route('competitor-analysis.delete', $analysis) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 ml-4" onclick="return confirm('Are you sure you want to delete this analysis?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    // Initialize Price Comparison Chart
    var priceComparisonChart = new ApexCharts(document.querySelector("#priceComparisonChart"), {
        series: [
            {
                name: 'Competitor Prices',
                data: @json($competitorPrices)
            }
        ],
        xaxis: {
            categories: @json($dateLabels),
            type: 'datetime',
            tickAmount: 10
        },
        yaxis: {
            labels: {
                formatter: function (value) {
                    return value.toFixed(2);
                }
            }
        },
        tooltip: {
            y: {
                formatter: function (value) {
                    return value.toFixed(2) + ' LKR';
                }
            }
        },
        title: {
            text: 'Competitor Price Trends',
            align: 'left'
        },
        legend: {
            position: 'top'
        },
        chart: {
            type: 'line',
            height: 400,
            zoom: {
                enabled: false
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'straight'
        },
        title: {
            text: 'Price Comparison Over Time',
            align: 'left'
        },
        grid: {
            row: {
                colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                opacity: 0.5
            },
        },
        xaxis: {
            categories: {{ Js::from($dateLabels) }}
        }
    });

    priceComparisonChart.render();

    // Apply Filters Function
    function applyFilters() {
        const competitor = document.getElementById('competitor').value;
        const product = document.getElementById('product').value;
        const dateRange = document.getElementById('date_range').value;

        window.location.href = `{{ route('competitor-analysis.dashboard') }}?competitor=${competitor}&product=${product}&date_range=${dateRange}`;
    }
</script>
@endpush
@endsection
