@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white dark:bg-gray-800">
                <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-6">Create Purchase Order</h2>
                
                <form action="{{ route('purchase-orders.store') }}" method="POST" id="purchaseOrderForm">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Supplier Selection -->
                        <div>
                            <label for="supplier_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Supplier <span class="text-red-500">*</span></label>
                            <select name="supplier_id" id="supplier_id" required
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                <option value="">Select a supplier</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Expected Delivery Date -->
                        <div>
                            <label for="expected_delivery_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Expected Delivery Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="expected_delivery_date" id="expected_delivery_date" required
                                class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                min="{{ date('Y-m-d') }}">
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-3">Order Items</h3>
                        
                        <div id="orderItems" class="space-y-4">
                            <!-- Dynamic items will be added here -->
                        </div>
                        
                        <div class="mt-4">
                            <button type="button" id="addItemBtn"
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="-ml-0.5 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                                </svg>
                                Add Item
                            </button>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg mb-6 border border-gray-200 dark:border-gray-600">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Order Summary</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Items</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white" id="totalItems">0</p>
                            </div>
                            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Quantity</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white" id="totalQuantity">0</p>
                            </div>
                            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Order Total</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white" id="orderTotal">Rs 0.00</p>
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="mb-6">
                        <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Notes (Optional)
                        </label>
                        <textarea name="notes" id="notes" rows="3"
                            class="shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-1 block w-full sm:text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-600 dark:text-white rounded-md"
                            placeholder="Any additional notes about this order"></textarea>
                    </div>

                    <!-- Total -->
                    <div class="flex justify-end mb-6">
                        <div class="text-right">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Total Amount:</p>
                            <p id="totalAmount" class="text-2xl font-bold text-gray-900 dark:text-white">Rs 0.00</p>
                            <input type="hidden" name="total_amount" id="totalAmountInput" value="0">
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <!-- Hidden field for total amount -->
                    <input type="hidden" name="total_amount" id="total_amount" value="0">
                    
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('purchase-orders.index') }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md shadow-sm text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Cancel
                        </a>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Create Purchase Order
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Item Template (Hidden) -->
<template id="itemTemplate">
    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 bg-gray-50 dark:bg-gray-700 order-item">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
            <div class="md:col-span-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Ingredient <span class="text-red-500">*</span>
                </label>
                <select name="items[__INDEX__][ingredient_id]" required
                    class="ingredient-select mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 dark:bg-gray-600 dark:text-white focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md"
                    data-index="__INDEX__">
                    <option value="">Select an ingredient</option>
                </select>
                <div class="stock-info mt-1 text-xs text-gray-500 dark:text-gray-400 hidden"></div>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Quantity <span class="text-red-500">*</span>
                </label>
                <div class="mt-1 flex rounded-md shadow-sm">
                    <input type="number" name="items[__INDEX__][quantity]" required step="0.01" min="0.01"
                        class="quantity-input focus:ring-blue-500 focus:border-blue-500 block w-full rounded-l-md sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-600 dark:text-white"
                        placeholder="0.00">
                    <span class="unit-display inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm whitespace-nowrap">
                        
                    </span>
                </div>
                <div class="suggested-quantity mt-1 text-xs text-blue-600 dark:text-blue-400"></div>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Unit Price (Rs.) <span class="text-red-500">*</span>
                </label>
                <input type="number" name="items[__INDEX__][unit_price]" required step="0.01" min="0.01"
                    class="price-input focus:ring-blue-500 focus:border-blue-500 block w-full rounded-md sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-600 dark:text-white"
                    placeholder="0.00">
            </div>
            <div class="md:col-span-3 flex items-end">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total:</p>
                    <p class="item-total text-lg font-semibold text-gray-900 dark:text-white">Rs. 0.00</p>
                </div>
            </div>
            <div class="md:col-span-1 flex items-end justify-end">
                <button type="button" class="remove-item-btn text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</template>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const orderItems = document.getElementById('orderItems');
        const addItemBtn = document.getElementById('addItemBtn');
        const itemTemplate = document.getElementById('itemTemplate');
        const supplierSelect = document.getElementById('supplier_id');
        let itemIndex = 0;
        
        // Ingredients data from the server
        const ingredients = @json($ingredients ?? []);
        
        // Low stock items passed from the controller
        const lowStockItems = @json($lowStockItems ?? []);
        let itemsAdded = false;
        
        // Add low stock items when the page loads if there are any
        if (lowStockItems.length > 0) {
            itemsAdded = true;
            // Group items by supplier
            const itemsBySupplier = lowStockItems.reduce((acc, item) => {
                if (!acc[item.supplier_id]) {
                    acc[item.supplier_id] = [];
                }
                acc[item.supplier_id].push(item);
                return acc;
            }, {});
            
            // If all items have the same supplier, select it
            const supplierIds = Object.keys(itemsBySupplier);
            if (supplierIds.length === 1) {
                supplierSelect.value = supplierIds[0];
                // Trigger change to update ingredient dropdowns
                const event = new Event('change');
                supplierSelect.dispatchEvent(event);
                
                // Add items after a short delay to allow the ingredient dropdown to update
                setTimeout(() => {
                    itemsBySupplier[supplierIds[0]].forEach(item => {
                        addItemWithValues(item);
                    });
                    // Recalculate totals after all items are added
                    setTimeout(calculateTotals, 100);
                }, 300);
            } else {
                // If multiple suppliers, just add all items
                lowStockItems.forEach(item => {
                    addItemWithValues(item);
                });
                // Recalculate totals after all items are added
                setTimeout(calculateTotals, 100);
            }
        }

        // Add new item when button is clicked - only if previous item has ingredient selected
        addItemBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation(); // Prevent any bubbling that might cause duplicate events
            
            // Get all items
            const items = document.querySelectorAll('.order-item');
            
            // If no items exist, allow adding first item
            if (items.length === 0) {
                addNewItem();
                return;
            }
            
            // Get the last item
            const lastItem = items[items.length - 1];
            const lastIngredientSelect = lastItem.querySelector('.ingredient-select');
            
            // Check if last item has ingredient selected
            if (lastIngredientSelect && lastIngredientSelect.value) {
                addNewItem();
                return;
            }
            
            // If validation fails
            alert('Please select an ingredient for the current item before adding a new one.');
            // Focus on the last item's ingredient select
            if (lastIngredientSelect) {
                lastIngredientSelect.focus();
            }
            // Return nothing to prevent any further execution
            return;
        });

        // Only add low stock items automatically, no default empty item
        if (itemsAdded) {
            // If we added low stock items, populate their dropdowns
            document.querySelectorAll('.ingredient-select').forEach(select => {
                populateIngredientDropdown(select);
            });
        }

        // Add new item function
        function addNewItem() {
            const newItem = document.importNode(itemTemplate.content, true);
            const itemElement = newItem.querySelector('.order-item');
            
            // Set unique IDs and names for the new item
            const newIndex = itemIndex++;
            const fields = itemElement.querySelectorAll('[id]');
            fields.forEach(field => {
                const id = field.id.replace('__INDEX__', newIndex);
                field.id = id;
                if (field.name) {
                    field.name = field.name.replace('[__INDEX__]', `[${newIndex}]`);
                }
            });
            
            // Add the item to the DOM
            orderItems.appendChild(itemElement);
            
            // Initialize the item
            initializeItem(itemElement);
            
            // Focus on the ingredient select
            const select = itemElement.querySelector('.ingredient-select');
            if (select) {
                select.focus();
            }
            
            // Update remove buttons state
            updateRemoveButtons();
            
            return itemElement;
            
            itemIndex++;
            updateRemoveButtons();
        }

        // Function to populate ingredient dropdown
        function populateIngredientDropdown(selectElement) {
            // Clear existing options except the first one
            while (selectElement.options.length > 1) {
                selectElement.remove(1);
            }
            
            // Add ingredients from the server data
            ingredients.forEach(ingredient => {
                const option = document.createElement('option');
                option.value = ingredient.id;
                option.textContent = `${ingredient.name} (${ingredient.unit_of_measure})` + 
                                    (ingredient.current_stock <= ingredient.minimum_stock ? ' - Low Stock' : '');
                option.dataset.unit = ingredient.unit_of_measure;
                option.dataset.currentStock = ingredient.current_stock;
                option.dataset.minStock = ingredient.minimum_stock;
                selectElement.appendChild(option);
            });
        }
        
        // Function to initialize event listeners for an item
        function initializeItemEventListeners(itemElement) {
            const select = itemElement.querySelector('.ingredient-select');
            if (select) {
                populateIngredientDropdown(select);
            }
            // Update unit of measure and stock info when ingredient changes
            const unitDisplay = itemElement.querySelector('.unit-display');
            const stockInfo = itemElement.querySelector('.stock-info');
            const suggestedQty = itemElement.querySelector('.suggested-quantity');
            const qtyInput = itemElement.querySelector('.quantity-input');
            
            function updateIngredientInfo() {
                const selectedOption = select.options[select.selectedIndex];
                if (!selectedOption || !selectedOption.value) {
                    unitDisplay.textContent = '';
                    stockInfo.classList.add('hidden');
                    suggestedQty.textContent = '';
                    return;
                }
                
                const unit = selectedOption.getAttribute('data-unit') || '';
                const currentStock = parseFloat(selectedOption.getAttribute('data-current-stock') || 0);
                const minStock = parseFloat(selectedOption.getAttribute('data-min-stock') || 0);
                
                // Update unit display
                unitDisplay.textContent = unit;
                
                // Update stock info
                if (!isNaN(currentStock) && !isNaN(minStock)) {
                    stockInfo.textContent = `Current: ${currentStock} ${unit} | Min: ${minStock} ${unit}`;
                    stockInfo.classList.remove('hidden');
                    
                    // Calculate and show suggested quantity (1.5x the difference between min and current)
                    const suggested = Math.max(0, Math.ceil((minStock * 1.5) - currentStock));
                    if (suggested > 0) {
                        suggestedQty.textContent = `Suggested: ${suggested} ${unit}`;
                        // Only auto-fill if the field is empty or hasn't been modified by the user
                        if (!qtyInput.dataset.userModified && (!qtyInput.value || parseFloat(qtyInput.value) <= 0)) {
                            qtyInput.value = suggested;
                        }
                    } else {
                        suggestedQty.textContent = 'Stock is sufficient';
                    }
                } else {
                    stockInfo.classList.add('hidden');
                    suggestedQty.textContent = '';
                }
                
                // Trigger calculation
                calculateItemTotal(itemElement);
            }
            
            // Track if user has modified the quantity
            const handleQtyInput = () => {
                qtyInput.dataset.userModified = 'true';
                calculateItemTotal(itemElement);
            };
            
            // Remove any existing event listeners to prevent duplicates
            qtyInput.removeEventListener('input', handleQtyInput);
            qtyInput.addEventListener('input', handleQtyInput);
            
            // Update on ingredient change
            const handleIngredientChange = (e) => {
                updateIngredientInfo();
                // Also update totals when ingredient changes
                calculateItemTotal(itemElement);
            };
            select.removeEventListener('change', handleIngredientChange);
            select.addEventListener('change', handleIngredientChange);
            
            // Initialize display
            updateIngredientInfo();
            
            // Remove item button
            const removeBtn = itemElement.querySelector('.remove-item-btn');
            if (removeBtn) {
                const handleRemoveClick = () => {
                    if (document.querySelectorAll('.order-item').length > 1) {
                        itemElement.remove();
                        calculateTotals();
                        updateRemoveButtons();
                    } else {
                        alert('At least one item is required');
                    }
                };
                removeBtn.removeEventListener('click', handleRemoveClick);
                removeBtn.addEventListener('click', handleRemoveClick);
            }
        }
        
        // Calculate total when quantity or price changes
        function calculateItemTotal(itemElement) {
            const quantity = parseFloat(itemElement.querySelector('.quantity-input').value) || 0;
            const price = parseFloat(itemElement.querySelector('.price-input').value) || 0;
            const total = quantity * price;
            
            const totalElement = itemElement.querySelector('.item-total');
            if (totalElement) {
                totalElement.textContent = 'Rs ' + total.toFixed(2);
            }
            
            // Validate quantity is positive
            const qtyInput = itemElement.querySelector('.quantity-input');
            if (quantity <= 0) {
                qtyInput.classList.add('border-red-500');
            } else {
                qtyInput.classList.remove('border-red-500');
            }
            
            // Validate price is positive
            const priceInput = itemElement.querySelector('.price-input');
            if (price < 0) {
                priceInput.classList.add('border-red-500');
            } else {
                priceInput.classList.remove('border-red-500');
            }
            
            calculateTotals();
        }
        
        // Calculate order total and update summary
        function calculateTotals() {
            let total = 0;
            let totalItems = 0;
            let totalQuantity = 0;
            
            // Get all items that have an ingredient selected
            const items = Array.from(document.querySelectorAll('.order-item'));
            
            // Calculate totals for all items with an ingredient selected
            items.forEach(item => {
                const ingredientSelect = item.querySelector('.ingredient-select');
                const quantity = parseFloat(item.querySelector('.quantity-input')?.value) || 0;
                const price = parseFloat(item.querySelector('.price-input')?.value) || 0;
                
                // Only count items with an ingredient selected
                if (ingredientSelect?.value) {
                    totalItems++;
                    
                    // Only add to total if quantity and price are valid
                    if (quantity > 0 && price >= 0.01) {
                        const itemTotal = quantity * price;
                        total += itemTotal;
                        totalQuantity += quantity;
                        
                        // Update item total display
                        const totalElement = item.querySelector('.item-total');
                        if (totalElement) {
                            totalElement.textContent = 'Rs ' + itemTotal.toFixed(2);
                        }
                    }
                }
            });
            
            // Update summary
            const orderTotalElement = document.getElementById('orderTotal');
            const totalItemsElement = document.getElementById('totalItems');
            const totalQuantityElement = document.getElementById('totalQuantity');
            
            if (orderTotalElement) orderTotalElement.textContent = 'Rs ' + total.toFixed(2);
            if (totalItemsElement) totalItemsElement.textContent = totalItems;
            if (totalQuantityElement) totalQuantityElement.textContent = totalQuantity.toFixed(2);
            
            // Update the hidden total amount field if it exists
            const totalAmountInput = document.getElementById('total_amount');
            if (totalAmountInput) {
                totalAmountInput.value = total.toFixed(2);
            }
        }
        
        // Update remove buttons state
        function updateRemoveButtons() {
            const items = document.querySelectorAll('.order-item');
            const removeButtons = document.querySelectorAll('.remove-item-btn');
            
            if (items.length > 1) {
                removeButtons.forEach(btn => {
                    btn.style.display = 'block';
                });
            } else {
                removeButtons.forEach(btn => {
                    btn.style.display = 'none';
                });
            }
        }
        
        // Function to initialize a new item
        function initializeItem(itemElement) {
            // Initialize event listeners for the item
            initializeItemEventListeners(itemElement);
            
            // Add click event for delete button
            const removeBtn = itemElement.querySelector('.remove-item-btn');
            if (removeBtn) {
                removeBtn.addEventListener('click', function() {
                    itemElement.remove();
                    calculateTotals();
                    updateRemoveButtons();
                });
            }
            
            // Initialize quantity input
            const qtyInput = itemElement.querySelector('.quantity-input');
            if (qtyInput) {
                const handleQtyInput = () => {
                    calculateItemTotal(itemElement);
                };
                qtyInput.removeEventListener('input', handleQtyInput);
                qtyInput.addEventListener('input', handleQtyInput);
            }
            
            // Initialize price input
            const priceInput = itemElement.querySelector('.price-input');
            if (priceInput) {
                const handlePriceInput = (e) => {
                    // Ensure price is at least 0.01
                    if (parseFloat(e.target.value) < 0.01) {
                        e.target.value = '0.01';
                    }
                    calculateItemTotal(itemElement);
                };
                priceInput.removeEventListener('input', handlePriceInput);
                priceInput.addEventListener('input', handlePriceInput);
                
                // Also add change event for when user clicks away
                const handlePriceChange = (e) => {
                    if (!e.target.value || parseFloat(e.target.value) < 0.01) {
                        e.target.value = '0.01';
                        calculateItemTotal(itemElement);
                    }
                };
                priceInput.removeEventListener('change', handlePriceChange);
                priceInput.addEventListener('change', handlePriceChange);
            }
            
            // Trigger initial calculation
            calculateItemTotal(itemElement);
        }
        
        // Initialize event listeners for all existing items
        document.querySelectorAll('.order-item').forEach(item => {
            initializeItem(item);
        });
        
        // If no items and no low stock items, add one empty item
        if (document.querySelectorAll('.order-item').length === 0 && (!lowStockItems || lowStockItems.length === 0)) {
            addNewItem();
        }
        
        // Add event listener for the add item button
        document.getElementById('addItemBtn')?.addEventListener('click', function() {
            const newItem = addItem();
            // Scroll to the new item
            newItem.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        });
        
        // Function to add a new item row with specific values
        function addItemWithValues(item) {
            const newItem = addItem(item.id, item.suggested_quantity, item.unit_price);
            
            // If the item has a unit of measure, update the display
            if (item.unit_of_measure) {
                const unitSpan = newItem.querySelector('.unit-of-measure');
                if (unitSpan) {
                    unitSpan.textContent = item.unit_of_measure;
                }
            }
            
            // If the item has a current and minimum stock, show as a hint
            if (item.current_stock !== undefined && item.minimum_stock !== undefined) {
                const stockInfo = newItem.querySelector('.stock-info');
                if (stockInfo) {
                    stockInfo.textContent = `Stock: ${item.current_stock} (Min: ${item.minimum_stock})`;
                    stockInfo.classList.remove('hidden');
                }
            }
            
            // Recalculate totals
            calculateTotals();
        }
        
        // Function to add a new item row
        function addItem(ingredientId = '', quantity = 1, unitPrice = '0.00') {
            const newItem = document.importNode(itemTemplate.content, true);
            const itemElement = newItem.querySelector('.order-item');
            
            // Set unique IDs and names for the new item
            const newIndex = itemIndex++;
            const fields = itemElement.querySelectorAll('[id]');
            fields.forEach(field => {
                const id = field.id.replace('0', newIndex);
                field.id = id;
                if (field.name) {
                    field.name = field.name.replace('[0]', `[${newIndex}]`);
                }
            });
            
            // Add the item to the DOM first
            orderItems.appendChild(itemElement);
            
            // Initialize the item
            initializeItem(itemElement);
            
            // Set the ingredient if provided (must be after initialization)
            if (ingredientId) {
                const select = itemElement.querySelector('select[name$="[ingredient_id]"]');
                if (select) {
                    // Set the value directly first
                    select.value = ingredientId;
                    
                    // Manually update the ingredient info
                    const unitDisplay = itemElement.querySelector('.unit-display');
                    if (unitDisplay) {
                        const selectedOption = select.options[select.selectedIndex];
                        if (selectedOption) {
                            unitDisplay.textContent = selectedOption.getAttribute('data-unit') || '';
                        }
                    }
                    
                    // Trigger change to update other fields
                    const event = new Event('change');
                    select.dispatchEvent(event);
                }
                
                // Set quantity if provided
                if (quantity) {
                    const qtyInput = itemElement.querySelector('.quantity-input');
                    if (qtyInput) {
                        qtyInput.value = quantity;
                    }
                }
                
                // Set unit price if provided
                if (unitPrice) {
                    const priceInput = itemElement.querySelector('.price-input');
                    if (priceInput) {
                        priceInput.value = unitPrice;
                    }
                }
                
                // Trigger calculation after a short delay to ensure DOM is updated
                setTimeout(() => {
                    calculateItemTotal(itemElement);
                }, 50);
            }
            
            // Set quantity and unit price if provided
            if (quantity) {
                const qtyInput = itemElement.querySelector('input[name$="[quantity]"]');
                if (qtyInput) qtyInput.value = quantity;
            }
            
            if (unitPrice) {
                const priceInput = itemElement.querySelector('input[name$="[unit_price]"]');
                if (priceInput) priceInput.value = unitPrice;
            }
            
            // Add the new item to the DOM
            orderItems.appendChild(newItem);
            
            // Initialize any necessary event listeners for the new item
            initializeItemEventListeners(itemElement);
            
            return itemElement;
        }
        
        // Form validation
        document.getElementById('purchaseOrderForm').addEventListener('submit', function(e) {
            // Recalculate totals before form submission to ensure amounts are up to date
            calculateTotals();
            
            const items = document.querySelectorAll('.order-item');
            let isValid = true;
            let hasValidItems = false;
            
            // Validate each item
            items.forEach(item => {
                const quantity = parseFloat(item.querySelector('.quantity-input').value) || 0;
                const price = parseFloat(item.querySelector('.price-input').value) || 0;
                const ingredient = item.querySelector('.ingredient-select').value;
                
                // Check if item is valid
                if (ingredient && quantity > 0 && price >= 0) {
                    hasValidItems = true;
                }
                
                // Highlight invalid fields
                if (!ingredient) {
                    item.querySelector('.ingredient-select').classList.add('border-red-500');
                    isValid = false;
                } else {
                    item.querySelector('.ingredient-select').classList.remove('border-red-500');
                }
                
                if (quantity <= 0) {
                    item.querySelector('.quantity-input').classList.add('border-red-500');
                    isValid = false;
                } else {
                    item.querySelector('.quantity-input').classList.remove('border-red-500');
                }
                
                if (price < 0) {
                    item.querySelector('.price-input').classList.add('border-red-500');
                    isValid = false;
                } else {
                    item.querySelector('.price-input').classList.remove('border-red-500');
                }
            });
            
            // Check supplier
            const supplierSelect = document.getElementById('supplier_id');
            if (!supplierSelect.value) {
                supplierSelect.classList.add('border-red-500');
                isValid = false;
                // Scroll to the supplier select
                supplierSelect.scrollIntoView({ behavior: 'smooth', block: 'center' });
            } else {
                supplierSelect.classList.remove('border-red-500');
            }
            
            // Check expected delivery date
            const deliveryDate = document.getElementById('expected_delivery_date');
            if (!deliveryDate.value) {
                deliveryDate.classList.add('border-red-500');
                isValid = false;
                // Scroll to the delivery date input
                deliveryDate.scrollIntoView({ behavior: 'smooth', block: 'center' });
            } else {
                deliveryDate.classList.remove('border-red-500');
            }
            
            // Show appropriate error messages
            if (!hasValidItems) {
                e.preventDefault();
                alert('Please add at least one valid item to the order with quantity greater than 0.');
                return false;
            }
            
            if (!isValid) {
                e.preventDefault();
                alert('Please fix the highlighted fields before submitting.');
                return false;
            }
            
            // If all validations pass, show loading state and submit
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = `
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Creating Order...
                `;
            }
        });
        
        // This block is intentionally left empty as the validation is now handled in the form submit event
    });
</script>
@endpush

<style>
    .remove-item-btn {
        transition: color 0.2s ease-in-out;
    }
    
    .remove-item-btn:hover {
        transform: scale(1.1);
    }
    
    .order-item {
        transition: all 0.3s ease;
    }
    
    .order-item-enter-active, .order-item-leave-active {
        transition: all 0.3s;
    }
    
    .order-item-enter, .order-item-leave-to {
        opacity: 0;
        transform: translateX(30px);
    }
</style>
@endsection