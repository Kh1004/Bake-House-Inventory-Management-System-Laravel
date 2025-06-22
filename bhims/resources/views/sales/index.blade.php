@extends('layouts.app')

@section('content')
<div class="py-2" x-data="{
    search: '{{ request('search') }}',
    status: '{{ request('status', '') }}',
    loading: false,
    
    init() {
        // Initialize with any URL parameters
        const urlParams = new URLSearchParams(window.location.search);
        this.search = urlParams.get('search') || '';
        this.status = urlParams.get('status') || '';
        
        // Handle initial page load with URL parameters
        this.$nextTick(() => {
            if (this.search || this.status) {
                this.applyFilters(false);
            }
        });
    },
    
    applyFilters(updateUrl = true) {
        this.loading = true;
        const params = new URLSearchParams();
        
        if (this.search) params.append('search', this.search);
        if (this.status) params.append('status', this.status);
        
        // Only add ajax flag if this is an AJAX request (not initial page load)
        if (updateUrl) {
            params.append('ajax', '1');
        }
        
        fetch(`{{ route('sales.index') }}?${params.toString()}`)
            .then(response => response.json())
            .then(data => {
                // Update the table content
                const tableContainer = document.getElementById('sales-table');
                const paginationContainer = document.getElementById('pagination-container');
                
                if (tableContainer) tableContainer.innerHTML = data.html;
                if (paginationContainer) paginationContainer.innerHTML = data.pagination || '';
                
                // Only update URL if this was triggered by a user action
                if (updateUrl) {
                    const cleanParams = new URLSearchParams(params);
                    cleanParams.delete('ajax');
                    const newUrl = `${window.location.pathname}${cleanParams.toString() ? '?' + cleanParams.toString() : ''}`;
                    window.history.pushState({}, '', newUrl);
                }
                
                // Reattach event listeners to pagination links
                this.attachPaginationListeners();
            })
            .catch(error => {
                console.error('Error:', error);
            })
            .finally(() => {
                this.loading = false;
            });
    },
    
    attachPaginationListeners() {
        // Handle pagination links
        document.querySelectorAll('#pagination-container a[href]').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const url = new URL(link.href);
                
                // Update the page parameter
                const page = url.searchParams.get('page');
                if (page) {
                    // Update URL without triggering a page reload
                    const cleanUrl = new URL(window.location);
                    cleanUrl.searchParams.set('page', page);
                    window.history.pushState({}, '', cleanUrl);
                    
                    // Trigger a new request with the updated page
                    const currentParams = new URLSearchParams(window.location.search);
                    if (this.search) currentParams.set('search', this.search);
                    if (this.status) currentParams.set('status', this.status);
                    currentParams.set('ajax', '1');
                    
                    fetch(`${window.location.pathname}?${currentParams.toString()}`)
                        .then(response => response.json())
                        .then(data => {
                            const tableContainer = document.getElementById('sales-table');
                            const paginationContainer = document.getElementById('pagination-container');
                            
                            if (tableContainer) tableContainer.innerHTML = data.html;
                            if (paginationContainer) paginationContainer.innerHTML = data.pagination || '';
                            
                            // Reattach event listeners for the new pagination links
                            this.attachPaginationListeners();
                        });
                }
            });
        });
    },
    
    resetFilters() {
        this.search = '';
        this.status = '';
        this.applyFilters();
    }
}">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                    <div class="mb-4 md:mb-0">
                        <h2 class="text-2xl font-semibold text-gray-800">Sales</h2>
                        <p class="text-sm text-gray-600 mt-1">View and manage sales transactions</p>
                    </div>
                    <div>
                        <a href="{{ route('sales.create') }}" 
                           class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            New Sale
                        </a>
                    </div>
                </div>

                <!-- Search and Filter -->
                <div class="mb-6 bg-gray-50 p-4 rounded-lg">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Search -->
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input type="text" 
                                       x-model="search"
                                       @input.debounce.500ms="applyFilters()"
                                       class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md" 
                                       placeholder="Search sales...">
                            </div>
                        </div>

                        <!-- Status Filter -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select id="status" 
                                    x-model="status"
                                    @change="applyFilters()"
                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="">All Statuses</option>
                                <option value="paid">Paid</option>
                                <option value="pending">Pending</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>

                        <!-- Date Range -->
                        <div>
                            <label for="date_range" class="block text-sm font-medium text-gray-700 mb-1">Date Range</label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input type="text" 
                                       id="date_range"
                                       class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md" 
                                       placeholder="Select date range">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4 flex justify-end">
                        <button type="button" 
                                @click="resetFilters()"
                                class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Reset Filters
                        </button>
                    </div>
                </div>

                <!-- Loading State -->
                <div x-show="loading" class="text-center py-4">
                    <div class="inline-flex items-center px-4 py-2 font-semibold leading-6 text-sm shadow rounded-md text-white bg-indigo-500">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Processing...
                    </div>
                </div>

                <!-- Sales Table -->
                <div x-show="!loading">
                    @include('sales.partials.table', ['sales' => $sales])
                </div>

                <!-- Pagination -->
                <div id="pagination-container" class="mt-4">
                    {{ $sales->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Initialize date range picker
    document.addEventListener('DOMContentLoaded', function() {
        $('input[name="date_range"]').daterangepicker({
            opens: 'left',
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear'
            }
        });

        $('input[name="date_range"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
            // Trigger filter update
            // You'll need to add date range handling to your Alpine.js component
        });

        $('input[name="date_range"]').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
            // Trigger filter update
        });
    });
</script>
@endpush
@endsection
