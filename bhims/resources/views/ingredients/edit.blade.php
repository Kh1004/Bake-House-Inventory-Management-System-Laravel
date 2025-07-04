@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
            <div class="p-6 bg-white">
                <!-- Header -->
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-800">Edit Ingredient</h2>
                        <p class="text-sm text-gray-600 mt-1">Update the details for {{ $ingredient->name }}</p>
                    </div>
                    <a href="{{ route('ingredients.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                        Back to List
                    </a>
                </div>

                <!-- Form -->
                <form action="{{ route('ingredients.update', $ingredient) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="bg-white p-6 rounded-lg border border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-6 pb-2 border-b border-gray-100">Basic Information</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Ingredient Name *</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $ingredient->name) }}" required 
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
                                        <option value="{{ $category->id }}" {{ (old('category_id', $ingredient->category_id) == $category->id) ? 'selected' : '' }}>
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
                                <select id="supplier_id" name="supplier_id"
                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md @error('supplier_id') border-red-300 text-red-900 @enderror">
                                    <option value="">Select a supplier</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" {{ (old('supplier_id', $ingredient->supplier_id) == $supplier->id) ? 'selected' : '' }}>
                                            {{ $supplier->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('supplier_id')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Unit of Measure -->
                            <div>
                                <label for="unit_of_measure" class="block text-sm font-medium text-gray-700">Unit of Measure *</label>
                                <input type="text" name="unit_of_measure" id="unit_of_measure" value="{{ old('unit_of_measure', $ingredient->unit_of_measure) }}" required
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('unit_of_measure') border-red-300 text-red-900 @enderror"
                                    placeholder="e.g., g, kg, ml, L, pcs">
                                @error('unit_of_measure')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="flex items-center">
                                <div class="flex items-center h-5">
                                    <input id="is_active" name="is_active" type="checkbox" 
                                        {{ old('is_active', $ingredient->is_active) ? 'checked' : '' }}
                                        class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
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
                                    placeholder="Add any additional details about this ingredient">{{ old('description', $ingredient->description) }}</textarea>
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
                                <label for="current_stock" class="block text-sm font-medium text-gray-700">Current Stock *</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input type="number" name="current_stock" id="current_stock" 
                                        value="{{ old('current_stock', $ingredient->current_stock) }}" step="0.01" min="0" required
                                        class="block w-full pr-12 sm:text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 @error('current_stock') border-red-300 text-red-900 @enderror">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm" id="current_stock_unit">{{ $ingredient->unit_of_measure }}</span>
                                    </div>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Current quantity in stock</p>
                                @error('current_stock')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Minimum Stock -->
                            <div>
                                <label for="minimum_stock" class="block text-sm font-medium text-gray-700">Minimum Stock Level *</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input type="number" name="minimum_stock" id="minimum_stock" 
                                        value="{{ old('minimum_stock', $ingredient->minimum_stock) }}" step="0.01" min="0" required
                                        class="block w-full pr-12 sm:text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 @error('minimum_stock') border-red-300 text-red-900 @enderror">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm" id="minimum_stock_unit">{{ $ingredient->unit_of_measure }}</span>
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
                                        value="{{ old('unit_price', $ingredient->unit_price) }}" step="0.01" min="0" required
                                        class="block w-full pl-10 pr-12 sm:text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 @error('unit_price') border-red-300 text-red-900 @enderror">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm" id="unit_price_unit">/{{ $ingredient->unit_of_measure }}</span>
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
                            Update Ingredient
                        </button>
                    </div>
                </form>

                <!-- Stock Movement History -->
                <div class="mt-10 bg-gray-50 p-6 rounded-lg">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Stock Movement History</h3>
                        <a href="{{ route('ingredients.adjust-stock', $ingredient) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                            <svg class="-ml-0.5 mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Adjust Stock
                        </a>
                    </div>
                    
                    @if($ingredient->stockMovements->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">By</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($ingredient->stockMovements->sortByDesc('created_at') as $movement)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $movement->created_at->format('M d, Y H:i') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($movement->movement_type === 'addition')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        Addition
                                                    </span>
                                                @elseif($movement->movement_type === 'subtraction')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                        Subtraction
                                                    </span>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                        {{ ucfirst($movement->movement_type) }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm {{ $movement->movement_type === 'addition' ? 'text-green-600' : 'text-red-600' }}">
                                                {{ $movement->movement_type === 'addition' ? '+' : '-' }}{{ number_format($movement->quantity, 2) }} {{ $ingredient->unit_of_measure }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $movement->user->name ?? 'System' }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500">
                                                {{ $movement->notes }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-gray-500">No stock movement history available.</p>
                        </div>
                    @endif
                </div>
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
    });
</script>
@endpush
@endsection