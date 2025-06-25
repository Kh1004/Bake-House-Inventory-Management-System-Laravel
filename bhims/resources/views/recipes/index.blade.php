@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Recipes</h1>
        <a href="{{ route('recipes.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
            Add New Recipe
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded" role="alert">
            <p>{{ session('error') }}</p>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-4 border-b border-gray-200">
            <form id="filter-form" method="GET" action="{{ route('recipes.index') }}" class="flex flex-col md:flex-row md:items-center md:justify-between w-full">
                <div class="relative w-full md:w-64 mb-4 md:mb-0">
                    <input type="text" name="search" id="search" placeholder="Search recipes..." 
                           value="{{ request('search') }}" 
                           class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           autocomplete="off">
                    <div class="absolute left-3 top-2.5 text-gray-400">
                        <i class="fas fa-search"></i>
                    </div>
                </div>
                <div class="flex space-x-2">
                    <select name="status" id="filter-status" class="border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="" {{ !request('status') ? 'selected' : '' }}>All Status</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    <a href="{{ route('recipes.index') }}" id="reset-filters" class="px-4 py-2 border rounded-lg hover:bg-gray-50 flex items-center">
                        <i class="fas fa-undo mr-1"></i> Reset
                    </a>
                </div>
            </form>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Name
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Servings
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Cost/Serving
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Selling Price
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="relative px-6 py-3">
                            <span class="sr-only">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 transition-all duration-300 ease-in-out" id="recipes-container">
                    @include('recipes.partials.recipe_rows', ['recipes' => $recipes])
                </tbody>
            </table>
        </div>
        
        @if($recipes->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $recipes->links() }}
            </div>
        @endif
    </div>
    </div>

@push('scripts')
<script>
    // Initialize variables
    const searchForm = document.getElementById('filter-form');
    const searchInput = document.getElementById('search');
    const statusFilter = document.getElementById('filter-status');
    const resetFilters = document.getElementById('reset-filters');
    const recipesContainer = document.getElementById('recipes-container');
    let isLoading = false;
    let debounceTimer;
    
    // Initialize event listeners
    document.addEventListener('DOMContentLoaded', function() {
        // Handle search input with debounce
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(updateFilters, 300);
            });
        }
        
        // Handle status filter change
        if (statusFilter) {
            statusFilter.addEventListener('change', updateFilters);
        }
        
        // Handle reset filters
        if (resetFilters) {
            resetFilters.addEventListener('click', function(e) {
                e.preventDefault();
                window.location.href = "{{ route('recipes.index') }}";
            });
        }
    
    // Handle pagination clicks
    document.addEventListener('click', function(e) {
        const paginationLink = e.target.closest('.pagination a');
        if (paginationLink && !isLoading) {
            e.preventDefault();
            const url = new URL(paginationLink.href);
            loadPage(url);
        }
    });
    
    // Load a specific page via AJAX
    function loadPage(url) {
        if (isLoading) return;
        
        isLoading = true;
        
        // Show loading state
        if (recipesContainer) {
            recipesContainer.style.opacity = '0.5';
            recipesContainer.style.pointerEvents = 'none';
        }
        
        // Add ajax parameter to the URL
        const fetchUrl = new URL(url);
        fetchUrl.searchParams.set('ajax', '1');
        
        fetch(fetchUrl, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            credentials: 'same-origin'
        })
        .then(handleResponse)
        .catch(handleError);
    }
    
    // Update filters with AJAX
    function updateFilters() {
        if (isLoading) return;
        
        const formData = new FormData(searchForm);
        const params = new URLSearchParams();
        
        // Add form data to params
        for (let [key, value] of formData.entries()) {
            if (value) params.set(key, value);
        }
        
        // Reset to first page when changing filters
        params.set('page', '1');
        
        // Create new URL with updated params
        const url = new URL(window.location.origin + window.location.pathname);
        url.search = params.toString();
        
        // Update browser URL without reloading the page
        window.history.pushState({}, '', url);
        
        // Load the filtered results
        loadPage(url);
    }
    
    // Handle AJAX response
    function handleResponse(response) {
        isLoading = false;
        
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        
        return response.json().then(data => {
            if (!data.success) {
                throw new Error(data.message || 'Error loading data');
            }
            
            // Fade out current content
            if (recipesContainer) {
                recipesContainer.style.opacity = '0';
                
                // Wait for fade out animation
                return new Promise(resolve => {
                    setTimeout(() => {
                        // Update content
                        if (data.html && recipesContainer) {
                            recipesContainer.innerHTML = data.html;
                        }
                        
                        // Update pagination if available
                        if (data.pagination) {
                            const paginationContainer = document.querySelector('.pagination');
                            if (paginationContainer) {
                                paginationContainer.outerHTML = data.pagination;
                            }
                        }
                        
                        // Fade in new content
                        setTimeout(() => {
                            if (recipesContainer) {
                                recipesContainer.style.opacity = '1';
                                recipesContainer.style.pointerEvents = 'auto';
                            }
                            resolve(data);
                        }, 50);
                    }, 200);
                });
            }
            return data;
        });
    }
    
    // Handle AJAX errors
    function handleError(error) {
        console.error('Error:', error);
        // Fallback to normal form submission if AJAX fails
        if (searchForm) {
            searchForm.removeEventListener('submit', arguments.callee);
            searchForm.submit();
        }
    }
    
    // Toggle recipe status
    async function toggleRecipeStatus(recipeId) {
        if (!confirm('Are you sure you want to toggle the status of this recipe?')) {
            return;
        }
        
        const toggleBtn = document.getElementById(`toggle-${recipeId}`);
        const toggleIcon = document.getElementById(`toggle-icon-${recipeId}`);
        const row = document.getElementById(`recipe-row-${recipeId}`);
        
        if (!toggleBtn || !toggleIcon) {
            console.error('Toggle elements not found');
            return;
        }
        
        // Disable button during request
        toggleBtn.disabled = true;
        
        try {
            const response = await fetch(`/recipes/${recipeId}/toggle-status`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            
            if (data.success) {
                // Toggle the UI state
                const isActive = data.is_active;
                
                // Update button classes
                toggleBtn.classList.toggle('bg-blue-500', isActive);
                toggleBtn.classList.toggle('bg-gray-300', !isActive);
                toggleBtn.title = isActive ? 'Deactivate' : 'Activate';
                
                // Update toggle thumb
                const thumb = toggleBtn.firstElementChild;
                if (thumb) {
                    thumb.style.transform = isActive ? 'translateX(1.5rem)' : 'translateX(0)';
                }
                
                // Update icon
                toggleIcon.textContent = isActive ? '✓' : '✕';
                toggleIcon.classList.toggle('text-blue-500', isActive);
                toggleIcon.classList.toggle('text-gray-400', !isActive);
                
                // If we're filtering by status, remove the row if it no longer matches the filter
                const currentStatus = new URLSearchParams(window.location.search).get('status');
                if ((currentStatus === 'active' && !isActive) || 
                    (currentStatus === 'inactive' && isActive)) {
                    // Fade out and remove row
                    row.style.opacity = '0';
                    setTimeout(() => {
                        row.remove();
                        
                        // If no more rows, show empty state
                        const tbody = document.querySelector('tbody');
                        if (tbody && tbody.children.length === 0) {
                            const emptyRow = document.createElement('tr');
                            emptyRow.innerHTML = `
                                <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                    No recipes found. <a href="{{ route('recipes.create') }}" class="text-blue-600 hover:underline">Create one now</a>.
                                </td>`;
                            tbody.appendChild(emptyRow);
                        }
                    }, 200);
                }
                
                // Show success message
                const toast = document.createElement('div');
                toast.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg';
                toast.textContent = 'Recipe status updated successfully';
                document.body.appendChild(toast);
                setTimeout(() => {
                    toast.remove();
                }, 3000);
                
            } else {
                throw new Error(data.message || 'Failed to update recipe status');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error: ' + (error.message || 'Failed to update recipe status'));
        } finally {
            toggleBtn.disabled = false;
        }
    }

    }); // End of DOMContentLoaded
</script>
@endpush

<style>
    /* Modern Toggle Switch */
    .toggle-btn {
        -webkit-tap-highlight-color: transparent;
        outline: none;
        user-select: none;
    }
    
    .toggle-btn:focus {
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
    }
    
    /* Smooth transitions */
    .toggle-btn,
    .toggle-btn * {
        transition: all 0.2s ease-in-out;
    }
    
    /* Active state */
    .toggle-btn.bg-blue-500 {
        background-color: #3b82f6 !important;
    }
    
    /* Hover states */
    .toggle-btn:hover:not(:disabled) {
        opacity: 0.9;
    }
    
    .toggle-btn:active:not(:disabled) {
        transform: scale(0.96);
    }
    
    /* Disabled state */
    .toggle-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    
    /* Responsive adjustments */
    @media (max-width: 640px) {
        .toggle-btn {
            width: 2.75rem;
            height: 1.5rem;
        }
        .toggle-btn span {
            width: 1.25rem;
            height: 1.25rem;
        }
        .toggle-btn.bg-blue-500 span {
            transform: translateX(1.5rem) !important;
        }
    }
</style>
@endsection
