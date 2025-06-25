@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-bold text-gray-800">Create New Recipe</h1>
            <a href="{{ route('recipes.index') }}" class="text-blue-600 hover:text-blue-800">
                <i class="fas fa-arrow-left mr-1"></i> Back to Recipes
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <form action="{{ route('recipes.store') }}" method="POST" id="recipe-form">
                @csrf
                
                <div class="p-6 space-y-6">
                    <!-- Basic Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Recipe Name *</label>
                            <input type="text" name="name" id="name" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                value="{{ old('name') }}">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="serving_size" class="block text-sm font-medium text-gray-700 mb-1">Serving Size *</label>
                            <input type="number" name="serving_size" id="serving_size" min="0.1" step="0.1" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                value="{{ old('serving_size', 1) }}">
                            @error('serving_size')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="selling_price" class="block text-sm font-medium text-gray-700 mb-1">Selling Price *</label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm" id="currency-symbol">LKR</span>
                                </div>
                                <input type="number" 
                                       name="selling_price" 
                                       id="selling_price" 
                                       min="0" 
                                       step="0.01" 
                                       required
                                       data-price-input="{{ old('selling_price', 0) }}"
                                       class="pl-14 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                       value="{{ old('selling_price', 0) }}">
                            </div>
                            @error('selling_price')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="flex items-end">
                            <div class="flex items-center h-10">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" id="is_active" value="1" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label for="is_active" class="ml-2 block text-sm text-gray-700">
                                    Active Recipe
                                </label>
                            </div>
                        </div>
                    </div>


                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" id="description" rows="2"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="instructions" class="block text-sm font-medium text-gray-700 mb-1">Instructions *</label>
                        <textarea name="instructions" id="instructions" rows="6" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ old('instructions') }}</textarea>
                        @error('instructions')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Ingredients Section -->
                    <div class="mt-8">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Ingredients</h3>
                            <button type="button" id="add-ingredient" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fas fa-plus mr-1"></i> Add Ingredient
                            </button>
                        </div>

                        <div id="ingredients-container" class="space-y-4">
                            <!-- Ingredient rows will be added here dynamically -->
                            @if(old('ingredients'))
                                @foreach(old('ingredients') as $index => $ingredient)
                                    <div class="ingredient-row bg-gray-50 p-4 rounded-lg border border-gray-200">
                                        <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                                            <div class="md:col-span-5">
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Ingredient *</label>
                                                <select name="ingredients[{{ $index }}][id]" required
                                                    class="ingredient-select w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                                    <option value="">Select Ingredient</option>
                                                    @foreach($ingredients as $ing)
                                                        <option value="{{ $ing->id }}" 
                                                            {{ old('ingredients.'.$index.'.id') == $ing->id ? 'selected' : '' }}
                                                            data-unit="{{ $ing->unit_of_measure }}">
                                                            {{ $ing->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="md:col-span-2">
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Quantity *</label>
                                                <input type="number" name="ingredients[{{ $index }}][quantity]" 
                                                    min="0.01" step="0.01" required
                                                    class="ingredient-quantity w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                                    value="{{ old('ingredients.'.$index.'.quantity') }}">
                                            </div>
                                            <div class="md:col-span-2">
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Unit *</label>
                                                <input type="text" name="ingredients[{{ $index }}][unit_of_measure]" required
                                                    class="ingredient-unit w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                                    value="{{ old('ingredients.'.$index.'.unit_of_measure') }}">
                                            </div>
                                            <div class="md:col-span-2">
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                                                <input type="text" name="ingredients[{{ $index }}][notes]"
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                                    value="{{ old('ingredients.'.$index.'.notes') }}">
                                            </div>
                                            <div class="flex items-end">
                                                <button type="button" class="remove-ingredient text-red-600 hover:text-red-800">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <div id="ingredient-error" class="mt-2 text-sm text-red-600"></div>
                    </div>
                </div>

                <div class="px-6 py-3 bg-gray-50 text-right">
                    <button type="button" onclick="window.history.back()" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cancel
                    </button>
                    <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Save Recipe
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        // Listen for currency changes
        window.addEventListener('currency-changed', (event) => {
            const { currency } = event.detail;
            // Update currency symbol
            document.getElementById('currency-symbol').textContent = currency;
            
            // Store the selected currency in the form for server-side processing
            const currencyInput = document.getElementById('currency-input');
            if (!currencyInput) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'currency';
                input.id = 'currency-input';
                input.value = currency;
                document.getElementById('recipe-form').appendChild(input);
            } else {
                currencyInput.value = currency;
            }
        });
    });
    
    document.addEventListener('DOMContentLoaded', function() {
        const ingredientsContainer = document.getElementById('ingredients-container');
        const addIngredientBtn = document.getElementById('add-ingredient');
        const ingredientError = document.getElementById('ingredient-error');
        let ingredientIndex = {{ old('ingredients') ? count(old('ingredients')) : 0 }};
        const selectedIngredients = new Set();
        
        // Function to update ingredient dropdowns
        function updateIngredientDropdowns() {
            const selects = document.querySelectorAll('.ingredient-select');
            selects.forEach(select => {
                const currentValue = select.value;
                Array.from(select.options).forEach(option => {
                    if (option.value && option.value !== '') {
                        option.disabled = selectedIngredients.has(parseInt(option.value)) && option.value !== currentValue;
                    }
                });
            });
        }
        
        // Add new ingredient row
        function addIngredientRow(ingredient = null) {
            const row = document.createElement('div');
            row.className = 'ingredient-row bg-gray-50 p-4 rounded-lg border border-gray-200';
            
            // Create select options
            let selectOptions = '<option value="">Select Ingredient</option>';
            @foreach($ingredients as $ingredient)
                selectOptions += `<option value="{{ $ingredient->id }}" 
                    data-unit="{{ $ingredient->unit_of_measure }}"
                    ${selectedIngredients.has({{ $ingredient->id }}) ? 'disabled' : ''}>
                    {{ $ingredient->name }}
                </option>`;
            @endforeach
            
            row.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                    <div class="md:col-span-5">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ingredient *</label>
                        <select name="ingredients[${ingredientIndex}][id]" required
                            class="ingredient-select w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            ${selectOptions}
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Quantity *</label>
                        <input type="number" name="ingredients[${ingredientIndex}][quantity]" 
                            min="0.01" step="0.01" required
                            class="ingredient-quantity w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            value="1">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Unit *</label>
                        <input type="text" name="ingredients[${ingredientIndex}][unit_of_measure]" required
                            class="ingredient-unit w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            value="">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                        <input type="text" name="ingredients[${ingredientIndex}][notes]"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="flex items-end">
                        <button type="button" class="remove-ingredient text-red-600 hover:text-red-800">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>`;
            
            ingredientsContainer.appendChild(row);
            
            // Add event listener for the new remove button
            row.querySelector('.remove-ingredient').addEventListener('click', function() {
                // Get the selected ingredient ID before removing
                const select = row.querySelector('.ingredient-select');
                if (select && select.value) {
                    selectedIngredients.delete(parseInt(select.value));
                    updateIngredientDropdowns();
                }
                row.remove();
                updateIngredientIndexes();
                validateIngredients();
            });
            
            // Add event listener for ingredient select change
            const select = row.querySelector('.ingredient-select');
            const unitInput = row.querySelector('.ingredient-unit');
            
            select.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const unit = selectedOption.getAttribute('data-unit') || '';
                unitInput.value = unit;
                
                // Update selected ingredients set
                const oldValue = this.getAttribute('data-previous-value');
                if (oldValue) {
                    selectedIngredients.delete(parseInt(oldValue));
                }
                if (this.value) {
                    selectedIngredients.add(parseInt(this.value));
                }
                this.setAttribute('data-previous-value', this.value);
                
                updateIngredientDropdowns();
                validateIngredients();
            });
            
            // Add event listeners for validation
            select.addEventListener('change', validateIngredients);
            row.querySelector('.ingredient-quantity').addEventListener('input', validateIngredients);
            
            ingredientIndex++;
            validateIngredients();
        }
        
        // Update indexes of ingredient rows
        function updateIngredientIndexes() {
            const rows = document.querySelectorAll('.ingredient-row');
            rows.forEach((row, index) => {
                // Update all inputs and selects in the row
                row.querySelectorAll('input, select').forEach(input => {
                    input.name = input.name.replace(/\[\d+\]/, `[${index}]`);
                });
            });
            ingredientIndex = rows.length;
        }
        
        // Validate that at least one ingredient is added
        function validateIngredients() {
            const rows = document.querySelectorAll('.ingredient-row');
            const hasValidIngredients = rows.length > 0 && Array.from(rows).every(row => {
                const select = row.querySelector('.ingredient-select');
                const quantity = row.querySelector('.ingredient-quantity');
                return select.value && quantity.value && parseFloat(quantity.value) > 0;
            });
            
            if (rows.length === 0) {
                ingredientError.textContent = 'Please add at least one ingredient.';
                return false;
            } else if (!hasValidIngredients) {
                ingredientError.textContent = 'Please fill in all required fields for each ingredient.';
                return false;
            } else {
                ingredientError.textContent = '';
                return true;
            }
        }
        
        // Add first ingredient row if none exists
        if (ingredientIndex === 0) {
            addIngredientRow();
        }
        
        // Add event listener for the add ingredient button
        addIngredientBtn.addEventListener('click', function() {
            // Check if there are any ingredients left to add
            const allIngredients = @json($ingredients->pluck('id')->toArray());
            const hasAvailableIngredients = allIngredients.some(id => !selectedIngredients.has(id));
            
            if (!hasAvailableIngredients) {
                alert('All available ingredients have been added to this recipe.');
                return;
            }
            
            addIngredientRow();
        });
        
        // Form submission validation
        document.getElementById('recipe-form').addEventListener('submit', function(e) {
            if (!validateIngredients()) {
                e.preventDefault();
            }
        });
        
        // Initialize existing ingredient selects with unit values
        document.querySelectorAll('.ingredient-select').forEach(select => {
            select.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const unit = selectedOption.getAttribute('data-unit') || '';
                const row = this.closest('.ingredient-row');
                const unitInput = row.querySelector('.ingredient-unit');
                if (unitInput && !unitInput.value) {
                    unitInput.value = unit;
                }
            });
            
            // Trigger change event to set initial unit if needed
            if (select.value) {
                select.dispatchEvent(new Event('change'));
            }
        });
        
        // Add remove button handlers for existing ingredient rows
        document.querySelectorAll('.remove-ingredient').forEach(button => {
            button.addEventListener('click', function() {
                this.closest('.ingredient-row').remove();
                updateIngredientIndexes();
                validateIngredients();
            });
        });
    });
</script>
@endpush

@endsection
