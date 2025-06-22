@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <!-- Header and Actions -->
                <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                    <div class="mb-4 md:mb-0">
                        <h2 class="text-2xl font-semibold text-gray-800">Ingredients Management</h2>
                        <p class="text-sm text-gray-600 mt-1">Manage your bakery's ingredients and inventory</p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="{{ route('ingredients.low-stock') }}" class="inline-flex items-center px-4 py-2 bg-yellow-100 border border-yellow-400 text-yellow-700 rounded-md font-medium hover:bg-yellow-200 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            Low Stock Alerts
                        </a>
                        <a href="{{ route('ingredients.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Add New Ingredient
                        </a>
                    </div>
                </div>

                <!-- Flash Messages -->
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
                                       value="{{ request('search') }}" 
                                       placeholder="Search by name..."
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            </div>
                            <div>
                                <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                                <select id="category" 
                                        class="mt-1 block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm">
                                    <option value="">All Categories</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex items-end space-x-2">
                                <button id="resetFilters" class="inline-flex items-center px-4 py-2 h-10 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    Reset
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Ingredients Table -->
                <div id="ingredients-table">
                    @include('ingredients.partials.table', ['ingredients' => $ingredients])
                </div>
                
                <!-- Pagination -->
                <div id="pagination-container" class="mt-4">
                    {{ $ingredients->withQueryString()->links() }}
                </div>
                
                @push('scripts')
                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const searchInput = document.getElementById('search');
                    const categorySelect = document.getElementById('category');
                    const resetButton = document.getElementById('resetFilters');
                    const ingredientsTable = document.getElementById('ingredients-table');
                    let debounceTimer;
                    
                    // Initialize with current URL parameters
                    const urlParams = new URLSearchParams(window.location.search);
                    const initialSearch = urlParams.get('search') || '';
                    const initialCategory = urlParams.get('category') || '';
                    
                    // Set initial values
                    searchInput.value = initialSearch;
                    if (categorySelect) {
                        categorySelect.value = initialCategory;
                    }

                    function showLoading() {
                        ingredientsTable.innerHTML = `
                            <div class="text-center py-8">
                                <div class="inline-block animate-spin rounded-full h-8 w-8 border-4 border-blue-500 border-t-transparent"></div>
                                <p class="mt-2 text-gray-600">Loading...</p>
                            </div>`;
                    }

                    function showError() {
                        ingredientsTable.innerHTML = `
                            <div class="text-center py-8 text-red-600">
                                <svg class="mx-auto h-12 w-12 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <p class="mt-2">Error loading data. Please try again.</p>
                            </div>`;
                    }

                    function updateURL(params) {
                        const newUrl = `${window.location.pathname}?${params.toString()}`;
                        window.history.pushState({ path: newUrl }, '', newUrl);
                    }

                    function fetchIngredients() {
                        const search = searchInput ? searchInput.value.trim() : '';
                        const category = categorySelect ? categorySelect.value : '';
                        
                        showLoading();
                        
                        const params = new URLSearchParams();
                        if (search) params.append('search', search);
                        if (category) params.append('category', category);
                        
                        // Update URL without page reload
                        const newUrl = `${window.location.pathname}?${params.toString()}`;
                        window.history.pushState({ path: newUrl }, '', newUrl);
                        
                        // Add ajax flag to the params for the request
                        params.append('ajax', '1');
                        
                        fetch(`${window.location.pathname}?${params.toString()}`)
                            .then(response => {
                                if (!response.ok) throw new Error('Network response was not ok');
                                return response.json();
                            })
                            .then(data => {
                                if (data && data.html) {
                                    // Update the table content
                                    ingredientsTable.innerHTML = data.html;
                                    
                                    // Update the pagination
                                    const paginationContainer = document.getElementById('pagination-container');
                                    if (paginationContainer) {
                                        if (data.pagination) {
                                            paginationContainer.innerHTML = data.pagination;
                                        } else {
                                            paginationContainer.innerHTML = '';
                                        }
                                    }
                                } else {
                                    showError();
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                showError();
                            });
                    }
                    
                    // Debounce function to prevent too many requests while typing
                    function debounceFetch() {
                        clearTimeout(debounceTimer);
                        debounceTimer = setTimeout(fetchIngredients, 300);
                    }
                    
                    // Event delegation for pagination links
                    document.addEventListener('click', function(e) {
                        if (e.target.closest('.pagination a')) {
                            e.preventDefault();
                            const url = new URL(e.target.closest('a').href);
                            
                            // Update the URL and form fields
                            window.history.pushState({}, '', url);
                            const searchParams = new URLSearchParams(url.search);
                            
                            if (searchInput) searchInput.value = searchParams.get('search') || '';
                            if (categorySelect) categorySelect.value = searchParams.get('category') || '';
                            
                            fetch(url + '&ajax=1')
                                .then(response => response.json())
                                .then(data => {
                                    if (data && data.html) {
                                        ingredientsTable.innerHTML = data.html;
                                        const paginationContainer = document.getElementById('pagination-container');
                                        if (paginationContainer) {
                                            paginationContainer.innerHTML = data.pagination || '';
                                        }
                                    }
                                });
                        }
                    });
                    
                    // Event listeners
                    searchInput.addEventListener('input', function() {
                        clearTimeout(debounceTimer);
                        debounceTimer = setTimeout(fetchIngredients, 300);
                    });
                    
                    if (categorySelect) {
                        categorySelect.addEventListener('change', function() {
                            clearTimeout(debounceTimer);
                            fetchIngredients();
                        });
                    }
                    
                    if (resetButton) {
                        resetButton.addEventListener('click', function() {
                            searchInput.value = '';
                            if (categorySelect) {
                                categorySelect.value = '';
                            }
                            // Clear the URL parameters
                            window.history.pushState({}, '', window.location.pathname);
                            fetchIngredients();
                        });
                    }
                    
                    // Handle browser back/forward
                    window.addEventListener('popstate', function() {
                        const params = new URLSearchParams(window.location.search);
                        searchInput.value = params.get('search') || '';
                        categorySelect.value = params.get('category') || '';
                        fetchIngredients();
                    });
                });
                </script>
                @endpush

                @push('scripts')
                <script>
                document.addEventListener('alpine:init', () => {
                    Alpine.data('ingredientDelete', () => ({
                        showModal: false,
                        ingredientId: null,
                        ingredientName: '',
                        
                        init() {
                            // Component initialization if needed
                        },
                        
                        openModal(id, name) {
                            this.ingredientId = id;
                            this.ingredientName = name;
                            this.showModal = true;
                        },
                        
                        closeModal() {
                            this.showModal = false;
                            this.ingredientId = null;
                            this.ingredientName = '';
                        },
                        
                        confirmDelete() {
                            if (this.ingredientId) {
                                const form = document.createElement('form');
                                form.method = 'POST';
                                form.action = `/ingredients/${this.ingredientId}`;
                                
                                // Add CSRF token
                                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                                const csrfInput = document.createElement('input');
                                csrfInput.type = 'hidden';
                                csrfInput.name = '_token';
                                csrfInput.value = csrfToken;
                                
                                // Add method spoofing for DELETE
                                const methodInput = document.createElement('input');
                                methodInput.type = 'hidden';
                                methodInput.name = '_method';
                                methodInput.value = 'DELETE';
                                
                                form.appendChild(csrfInput);
                                form.appendChild(methodInput);
                                
                                // Append form to body and submit
                                document.body.appendChild(form);
                                form.submit();
                            }
                        }
                    }));
                });
                </script>
                @endpush

                <!-- Delete Confirmation Modal -->
                <div x-data="ingredientDelete()" x-init="init()" @open-delete-modal.window="openModal($event.detail.id, $event.detail.name)" x-cloak>
                    <!-- Overlay -->
                    <div x-show="showModal" 
                         class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity z-50"
                         x-transition:enter="ease-out duration-300"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="ease-in duration-200"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         @click="closeModal">
                    </div>

                    <!-- Modal -->
                    <div x-show="showModal" 
                         class="fixed inset-0 z-50 overflow-y-auto"
                         aria-labelledby="modal-title"
                         role="dialog"
                         aria-modal="true">
                        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                                 @click.away="closeModal"
                                 x-transition:enter="ease-out duration-300"
                                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                 x-transition:leave="ease-in duration-200"
                                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                    <div class="sm:flex sm:items-start">
                                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                            <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                        </div>
                                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                                Delete Ingredient
                                            </h3>
                                            <div class="mt-2">
                                                <p class="text-sm text-gray-500">
                                                    Are you sure you want to delete <span class="font-medium" x-text="ingredientName"></span>? This action cannot be undone.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                    <button type="button"
                                            @click="confirmDelete"
                                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                                        Delete
                                    </button>
                                    <button type="button"
                                            @click="closeModal"
                                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                        Cancel
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
