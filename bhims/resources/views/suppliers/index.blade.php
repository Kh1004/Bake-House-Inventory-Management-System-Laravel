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
        
        // Handle browser back/forward buttons
        window.addEventListener('popstate', () => {
            const newParams = new URLSearchParams(window.location.search);
            const newSearch = newParams.get('search') || '';
            const newStatus = newParams.get('status') || '';
            
            if (this.search !== newSearch || this.status !== newStatus) {
                this.search = newSearch;
                this.status = newStatus;
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
        
        fetch(`{{ route('suppliers.index') }}?${params.toString()}`)
            .then(response => response.json())
            .then(data => {
                // Update the table content
                const tableContainer = document.getElementById('suppliers-table');
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
                            const tableContainer = document.getElementById('suppliers-table');
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
    },
    
    // This will be called when Alpine is initialized
    mounted() {
        // Check if we have any search parameters in the URL
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('search') || urlParams.has('status')) {
            this.search = urlParams.get('search') || '';
            this.status = urlParams.get('status') || '';
        }
    }
}" x-init="$nextTick(() => {
    attachPaginationListeners();
    
    // Watch for URL changes (browser back/forward buttons)
    window.addEventListener('popstate', () => {
        const urlParams = new URLSearchParams(window.location.search);
        this.search = urlParams.get('search') || '';
        this.status = urlParams.get('status') || '';
        this.applyFilters();
    });
})">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                    <div class="mb-4 md:mb-0">
                        <h2 class="text-2xl font-semibold text-gray-800">Suppliers</h2>
                        <p class="text-sm text-gray-600 mt-1">Manage your suppliers and their information</p>
                    </div>
                    <div>
                        <a href="{{ route('suppliers.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Add New Supplier
                        </a>
                    </div>
                </div>

                <!-- Flash Messages -->
                @if (session('success'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-200 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 p-4 bg-red-100 border border-red-200 text-red-700 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                @if (session('warning'))
                    <div class="mb-4 p-4 bg-yellow-100 border border-yellow-200 text-yellow-700 rounded">
                        {{ session('warning') }}
                    </div>
                @endif

                <!-- Filters -->
                <div class="mb-6">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                                <input type="text" id="search" x-model="search" @input.debounce.500ms="applyFilters()" placeholder="Search suppliers..." class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select id="status" x-model="status" @change="applyFilters()" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">All Status</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                            <div class="flex items-end">
                                <button @click="resetFilters()" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
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
                    <p class="mt-2 text-gray-600">Loading suppliers...</p>
                </div>

                <!-- Suppliers Table -->
                <div x-show="!loading" id="suppliers-table">
                    @include('suppliers.partials.table', ['suppliers' => $suppliers])
                </div>

                <!-- Pagination -->
                <div id="pagination-container" class="mt-4">
                    @if(request()->ajax())
                        {!! $suppliers->withQueryString()->links() !!}
                    @else
                        {{ $suppliers->withQueryString()->links() }}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
