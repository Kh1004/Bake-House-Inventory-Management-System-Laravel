@extends('layouts.app')

@section('content')
<div class="py-2" x-data="{
    sortBy: '{{ request('sort', 'name') }}',
    search: '{{ request('search', '') }}',
    loading: false,
    
    init() {
        // Initialize with any URL parameters
        const urlParams = new URLSearchParams(window.location.search);
        this.search = urlParams.get('search') || '';
        this.sortBy = urlParams.get('sort') || 'name';
    },
    
    applyFilters() {
        this.loading = true;
        const params = new URLSearchParams();
        
        if (this.search) params.append('search', this.search);
        if (this.sortBy) params.append('sort', this.sortBy);
        
        // Add ajax flag for JSON response
        params.append('ajax', '1');
        
        fetch(`{{ route('categories.index') }}?${params.toString()}`)
            .then(response => response.json())
            .then(data => {
                // Update the table content
                const tableContainer = document.getElementById('categories-table');
                const paginationContainer = document.getElementById('pagination-container');
                
                if (tableContainer) tableContainer.innerHTML = data.html;
                if (paginationContainer) paginationContainer.innerHTML = data.pagination || '';
                
                // Update URL without reloading the page
                const newUrl = `${window.location.pathname}?${params.toString().replace('&ajax=1', '')}`;
                window.history.pushState({ path: newUrl }, '', newUrl);
                
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
                window.history.pushState({}, '', url);
                
                // Update the page parameter and trigger a new request
                const page = url.searchParams.get('page');
                if (page) {
                    const currentParams = new URLSearchParams(window.location.search);
                    currentParams.set('page', page);
                    currentParams.set('ajax', '1');
                    
                    fetch(`${window.location.pathname}?${currentParams.toString()}`)
                        .then(response => response.json())
                        .then(data => {
                            const tableContainer = document.getElementById('categories-table');
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
    
    init() {
        // Initialize with any URL parameters
        const urlParams = new URLSearchParams(window.location.search);
        this.search = urlParams.get('search') || '';
        this.sortBy = urlParams.get('sort') || 'name';
        
        // Attach initial pagination listeners
        this.$nextTick(() => {
            this.attachPaginationListeners();
        });
    },
    
    resetFilters() {
        this.search = '';
        this.sortBy = 'name';
        this.applyFilters();
    }
}"
@filter-updated.window="applyFilters()">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                    <div class="mb-4 md:mb-0">
                        <h2 class="text-2xl font-semibold text-gray-800">Categories</h2>
                        <p class="text-sm text-gray-600 mt-1">Manage your ingredient categories</p>
                    </div>
                    <div>
                        <a href="{{ route('categories.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Add New Category
                        </a>
                    </div>
                </div>

                @if (session('success'))
                    <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-green-700">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700">{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Search and Filter -->
                <div class="mb-6">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                                <input type="text" 
                                       id="search" 
                                       x-model="search" 
                                       @input.debounce.500ms="applyFilters()"
                                       placeholder="Search by name or description..."
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            <div class="flex items-end space-x-2">
                                <button @click="resetFilters()" 
                                        class="inline-flex items-center px-4 py-2 h-10 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    Reset
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Loading State -->
                <div x-show="loading" class="text-center py-8">
                    <div class="inline-block animate-spin rounded-full h-8 w-8 border-4 border-indigo-500 border-t-transparent"></div>
                    <p class="mt-2 text-gray-600">Loading categories...</p>
                </div>

                <!-- Categories Table -->
                <div x-show="!loading" id="categories-table">
                    @include('categories.partials.table', ['categories' => $categories])
                </div>

                <!-- Pagination -->
                <div id="pagination-container" class="mt-4">
                    {{ $categories->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection