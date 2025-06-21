@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white dark:bg-gray-800">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                        Adjust Stock - {{ $ingredient->name }}
                    </h2>
                    <a href="{{ route('ingredients.show', $ingredient) }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                        Back to Ingredient
                    </a>
                </div>

                <div class="mb-6">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        <span class="font-medium">Current Stock:</span> 
                        <span class="font-semibold">{{ $ingredient->current_stock }} {{ $ingredient->unit_of_measure }}</span>
                    </p>
                    @if($ingredient->minimum_stock > 0)
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            <span class="font-medium">Minimum Required:</span> 
                            <span class="font-semibold">{{ $ingredient->minimum_stock }} {{ $ingredient->unit_of_measure }}</span>
                        </p>
                    @endif
                </div>

                <form action="{{ route('ingredients.adjust-stock', $ingredient) }}" method="POST">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Adjustment Type -->
                        <div class="col-span-1">
                            <label for="adjustment_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Adjustment Type
                            </label>
                            <select id="adjustment_type" name="adjustment_type" 
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600"
                                    required>
                                <option value="add">Add to Stock</option>
                                <option value="remove">Remove from Stock</option>
                                <option value="set">Set Exact Quantity</option>
                            </select>
                        </div>

                        <!-- Quantity -->
                        <div class="col-span-1">
                            <label for="quantity" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Quantity
                                <span id="quantityHelp" class="text-xs text-gray-500 dark:text-gray-400">(in {{ $ingredient->unit_of_measure }})</span>
                            </label>
                            <input type="number" id="quantity" name="quantity" 
                                   step="0.01" min="0.01" 
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600"
                                   required>
                        </div>

                        <!-- Notes -->
                        <div class="col-span-2">
                            <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Notes / Reason
                            </label>
                            <textarea id="notes" name="notes" rows="3"
                                      class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600"
                                      placeholder="e.g., Received new shipment, Used in production, etc." required></textarea>
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-end">
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            Update Stock
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const adjustmentType = document.getElementById('adjustment_type');
        const quantityInput = document.getElementById('quantity');
        const quantityHelp = document.getElementById('quantityHelp');
        
        function updateQuantityHelp() {
            const type = adjustmentType.value;
            let helpText = '';
            
            switch(type) {
                case 'add':
                    helpText = 'Enter the amount to add to current stock';
                    break;
                case 'remove':
                    helpText = 'Enter the amount to remove from current stock';
                    quantityInput.max = {{ $ingredient->current_stock }};
                    break;
                case 'set':
                    helpText = 'Enter the new total stock quantity';
                    break;
            }
            
            quantityHelp.textContent = helpText + ' (in {{ $ingredient->unit_of_measure }})';
            
            // Clear and focus the quantity field when type changes
            quantityInput.value = '';
            quantityInput.focus();
        }
        
        // Initialize help text
        updateQuantityHelp();
        
        // Update help text when adjustment type changes
        adjustmentType.addEventListener('change', updateQuantityHelp);
    });
</script>
@endpush
@endsection
