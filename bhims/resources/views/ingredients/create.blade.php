@extends('layouts.app')

@section('content')
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('ingredientForm');
        
        if (form) {
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                console.log('Form submission started');
                
                try {
                    const formData = new FormData(form);
                    console.log('Form data:', Object.fromEntries(formData.entries()));
                    
                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: new URLSearchParams(formData)
                    });

                    console.log('Response status:', response.status);
                    
                    if (response.redirected) {
                        console.log('Redirecting to:', response.url);
                        window.location.href = response.url;
                        return;
                    }

                    const data = await response.json().catch(() => ({}));
                    console.log('Response data:', data);

                    if (response.ok) {
                        window.location.href = '{{ route("ingredients.index") }}';
                    } else {
                        const errorMsg = data.message || 'An error occurred';
                        console.error('Server error:', errorMsg);
                        alert('Error: ' + errorMsg);
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Error submitting form: ' + error.message);
                }
            });
        }
    });
</script>
@endpush

<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                <p class="font-bold">Error</p>
                <p>{{ session('error') }}</p>
            </div>
        @endif
        @if($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                <p class="font-bold">Validation Error</p>
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <!-- Header -->
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-800">Add New Ingredient</h2>
                        <p class="text-sm text-gray-600 mt-1">Fill in the details below to add a new ingredient to your inventory</p>
                    </div>
                    <a href="{{ route('ingredients.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                        Back to List
                    </a>
                </div>

                <!-- Form -->
                <form id="ingredientForm" action="{{ route('ingredients.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" id="csrf-token">

                    <div class="bg-white p-6 rounded-lg border border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-6 pb-2 border-b border-gray-100">Basic Information</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Category -->
                            <div class="md:col-span-2">
                                <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>
                                <select id="category_id" name="category_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md @error('category_id') border-red-300 @enderror">
                                    <option value="">Select a category</option>
                                    @foreach(\App\Models\Category::orderBy('name')->get() as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Ingredient Name *</label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required 
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('name') border-red-300 text-red-900 @enderror">
                                @error('name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Category -->
                            <div>
                                <label for="category_id" class="block text-sm font-medium text-gray-700">Category *</label>
                                <select id="category_id" name="category_id" required
                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md @error('category_id') border-red-300 text-red-900 @enderror">
                                    <option value="">Select a category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Supplier -->
                            <div>
                                <label for="supplier_id" class="block text-sm font-medium text-gray-700">Supplier</label>
                                <select id="supplier_id" name="supplier_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md @error('supplier_id') border-red-300 @enderror">
                                    <option value="">Select a supplier</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                            {{ $supplier->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('supplier_id')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Unit of Measure -->
                            <div class="md:col-span-2">
                                <label for="unit_of_measure" class="block text-sm font-medium text-gray-700">Unit of Measure *</label>
                                <input type="text" name="unit_of_measure" id="unit_of_measure" value="{{ old('unit_of_measure', 'g') }}" required
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('unit_of_measure') border-red-300 text-red-900 @enderror"
                                    placeholder="e.g., g, kg, ml, L, pcs">
                                @error('unit_of_measure')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="flex items-center">
                                <div class="flex items-center h-5">
                                    <input id="is_active" name="is_active" type="checkbox" value="1"
                                        {{ old('is_active', true) ? 'checked' : '' }}
                                        class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                                    <input type="hidden" name="is_active" value="0">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="is_active" class="font-medium text-gray-700">Active</label>
                                    <p class="text-gray-500">Make this ingredient available for use</p>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mt-6">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <div class="mt-1">
                                <textarea id="description" name="description" rows="3" 
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border border-gray-300 rounded-md @error('description') border-red-300 @enderror"
                                    placeholder="Add any additional details about this ingredient">{{ old('description') }}</textarea>
                            </div>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-lg border border-gray-200 mt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-6 pb-2 border-b border-gray-100">Stock & Pricing</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Current Stock -->
                            <div>
                                <label for="current_stock" class="block text-sm font-medium text-gray-700">Initial Stock *</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input type="number" name="current_stock" id="current_stock" 
                                        value="{{ old('current_stock', 0) }}" step="0.01" min="0" required
                                        class="block w-full pr-12 sm:text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 @error('current_stock') border-red-300 text-red-900 @enderror">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm" id="current_stock_unit">g</span>
                                    </div>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Initial quantity in stock</p>
                                @error('current_stock')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Minimum Stock -->
                            <div>
                                <label for="minimum_stock" class="block text-sm font-medium text-gray-700">Minimum Stock Level *</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input type="number" name="minimum_stock" id="minimum_stock" 
                                        value="{{ old('minimum_stock', 0) }}" step="0.01" min="0" required
                                        class="block w-full pr-12 sm:text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 @error('minimum_stock') border-red-300 text-red-900 @enderror">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm" id="minimum_stock_unit">g</span>
                                    </div>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Alert when stock is below this level</p>
                                @error('minimum_stock')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Unit Price -->
                            <div>
                                <label for="unit_price" class="block text-sm font-medium text-gray-700">Unit Price *</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">Rs.</span>
                                    </div>
                                    <input type="number" name="unit_price" id="unit_price" 
                                        value="{{ old('unit_price', 0) }}" step="0.01" min="0" required
                                        class="block w-full pl-10 pr-12 sm:text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 @error('unit_price') border-red-300 text-red-900 @enderror">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm" id="unit_price_unit">/g</span>
                                    </div>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Cost per unit (LKR)</p>
                                @error('unit_price')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 mt-8">
                        <a href="{{ route('ingredients.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Cancel
                        </a>
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Save Ingredient
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Update unit labels when unit of measure changes
    document.addEventListener('DOMContentLoaded', function() {
        const unitOfMeasure = document.getElementById('unit_of_measure');
        const currentStockUnit = document.getElementById('current_stock_unit');
        const minimumStockUnit = document.getElementById('minimum_stock_unit');
        const unitPriceUnit = document.getElementById('unit_price_unit');

        function updateUnitLabels() {
            const unit = unitOfMeasure.value || 'unit';
            currentStockUnit.textContent = unit;
            minimumStockUnit.textContent = unit;
            unitPriceUnit.textContent = `/${unit}`;
        }

        unitOfMeasure.addEventListener('input', updateUnitLabels);
        updateUnitLabels(); // Initialize
    });
</script>
@endpush
@endsection