@extends('layouts.app')

@section('header', 'Create New Sale')

@section('content')
<div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
    <div class="px-4 py-5 sm:p-6">
        <form action="{{ route('sales.store') }}" method="POST" id="saleForm">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Customer Selection -->
                <div class="col-span-2">
                    <label for="customer_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Customer <span class="text-red-500">*</span>
                    </label>
                    <select name="customer_id" id="customer_id" required
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="">Select a customer</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Payment Status -->
                <div>
                    <label for="payment_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Payment Status <span class="text-red-500">*</span>
                    </label>
                    <select name="payment_status" id="payment_status" required
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="paid">Paid</option>
                        <option value="pending">Pending</option>
                        <option value="partial">Partial</option>
                    </select>
                </div>

                <!-- Notes -->
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Notes
                    </label>
                    <input type="text" name="notes" id="notes"
                           class="mt-1 block w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
            </div>

            <!-- Product Selection -->
            <div class="mt-8">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Products</h3>
                
                <div id="productRows">
                    <!-- Product rows will be added here by JavaScript -->
                </div>

                <div class="mt-4">
                    <button type="button" id="addProduct" 
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-indigo-800 dark:text-indigo-100 dark:hover:bg-indigo-700">
                        Add Product
                    </button>
                </div>
            </div>

            <!-- Totals -->
            <div class="mt-8 pt-4 border-t border-gray-200 dark:border-gray-700">
                <div class="flex justify-end">
                    <div class="w-64">
                        <div class="flex justify-between text-base font-medium text-gray-900 dark:text-white mb-2">
                            <span>Subtotal:</span>
                            <span id="subtotal">LKR 0.00</span>
                        </div>
                        <div class="flex justify-between text-base font-medium text-gray-900 dark:text-white mb-2">
                            <span>Tax (0%):</span>
                            <span id="tax">LKR 0.00</span>
                        </div>
                        <div class="flex justify-between text-lg font-bold text-gray-900 dark:text-white pt-2 border-t border-gray-200 dark:border-gray-600">
                            <span>Total:</span>
                            <span id="total">LKR 0.00</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="mt-8 flex justify-end">
                <a href="{{ route('sales.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:bg-gray-600">
                    Cancel
                </a>
                <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Create Sale
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Product Row Template (Hidden) -->
<template id="productRowTemplate">
    <div class="product-row grid grid-cols-12 gap-4 mb-4 items-end">
        <div class="col-span-5">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Product</label>
            <select name="items[__INDEX__][product_id]" class="product-select mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md" required>
                <option value="">Select a product</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" data-price="{{ $product->selling_price }}">
                        {{ $product->name }} (LKR {{ number_format($product->selling_price, 2) }})
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-span-2">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Quantity</label>
            <input type="number" name="items[__INDEX__][quantity]" min="0.01" step="0.01" value="1" required
                   class="quantity-input mt-1 block w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        </div>
        <div class="col-span-3">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Price</label>
            <div class="mt-1 flex rounded-md shadow-sm">
                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm dark:bg-gray-600 dark:border-gray-600 dark:text-gray-300">
                    LKR
                </span>
                <input type="number" step="0.01" min="0"
                       class="price-input flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                       disabled>
            </div>
        </div>
        <div class="col-span-1">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Total</label>
            <div class="mt-1 text-sm font-medium text-gray-900 dark:text-white item-total">LKR 0.00</div>
        </div>
        <div class="col-span-1">
            <button type="button" class="remove-product text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </button>
        </div>
    </div>
</template>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let productIndex = 0;
    const productRows = document.getElementById('productRows');
    const productRowTemplate = document.getElementById('productRowTemplate');
    const addProductBtn = document.getElementById('addProduct');
    const form = document.getElementById('saleForm');

    // Add first product row by default
    addProductRow();

    // Add product row
    addProductBtn.addEventListener('click', addProductRow);

    // Handle product selection and quantity changes
    productRows.addEventListener('change', function(e) {
        if (e.target.classList.contains('product-select') || e.target.classList.contains('quantity-input')) {
            updateRowTotal(e.target.closest('.product-row'));
            updateTotals();
        }
    });

    // Handle product removal
    productRows.addEventListener('click', function(e) {
        if (e.target.closest('.remove-product')) {
            const row = e.target.closest('.product-row');
            row.remove();
            updateTotals();
            
            // Don't allow removing the last row
            if (document.querySelectorAll('.product-row').length === 0) {
                addProductRow();
            }
        }
    });

    // Add a new product row
    function addProductRow() {
        const newRow = document.createElement('div');
        const currentIndex = productIndex++;
        
        // Create a new row with the current index
        newRow.innerHTML = productRowTemplate.innerHTML.replace(/__INDEX__/g, currentIndex);
        productRows.appendChild(newRow);
        
        // Initialize the price input
        const select = newRow.querySelector('.product-select');
        if (select) {
            updatePriceInput(select);
        }
        
        // Add event listener for product selection
        select.addEventListener('change', function() {
            updatePriceInput(this);
            updateRowTotal(this.closest('.product-row'));
            updateTotals();
        });
        
        // Add event listener for quantity changes
        const quantityInput = newRow.querySelector('.quantity-input');
        if (quantityInput) {
            quantityInput.addEventListener('input', function() {
                updateRowTotal(this.closest('.product-row'));
                updateTotals();
            });
        }
        
        // Trigger change to update totals
        if (select && select.value) {
            updatePriceInput(select);
            updateRowTotal(newRow);
            updateTotals();
        }
    }

    // Update the price input when a product is selected
    function updatePriceInput(selectElement) {
        const row = selectElement.closest('.product-row');
        const selectedOption = selectElement.options[selectElement.selectedIndex];
        const priceInput = row.querySelector('.price-input');
        
        if (selectedOption && selectedOption.value) {
            const price = selectedOption.getAttribute('data-price');
            priceInput.value = parseFloat(price).toFixed(2);
        } else {
            priceInput.value = '0.00';
        }
    }

    // Update the total for a single row
    function updateRowTotal(row) {
        const select = row.querySelector('.product-select');
        const quantityInput = row.querySelector('.quantity-input');
        const itemTotal = row.querySelector('.item-total');
        
        if (!select || !select.value || !quantityInput || !quantityInput.value) {
            itemTotal.textContent = 'LKR 0.00';
            return;
        }
        
        const price = parseFloat(select.options[select.selectedIndex].getAttribute('data-price'));
        const quantity = parseFloat(quantityInput.value);
        const total = price * quantity;
        
        itemTotal.textContent = 'LKR ' + total.toFixed(2);
    }

    // Update the subtotal, tax, and total
    function updateTotals() {
        let subtotal = 0;
        
        document.querySelectorAll('.product-row').forEach(row => {
            const itemTotal = row.querySelector('.item-total');
            if (itemTotal) {
                const amount = parseFloat(itemTotal.textContent.replace('LKR', '')) || 0;
                subtotal += amount;
            }
        });
        
        // For now, tax is 0%. You can add tax calculation logic here if needed
        const tax = 0;
        const total = subtotal + tax;
        
        document.getElementById('subtotal').textContent = 'LKR ' + subtotal.toFixed(2);
        document.getElementById('tax').textContent = 'LKR ' + tax.toFixed(2);
        document.getElementById('total').textContent = 'LKR ' + total.toFixed(2);
        
        // Update the hidden total field in the form if it exists
        const totalInput = document.getElementById('total_amount');
        if (totalInput) {
            totalInput.value = total.toFixed(2);
        }
    }
    
    // Add a hidden field for the total amount
    const totalInput = document.createElement('input');
    totalInput.type = 'hidden';
    totalInput.name = 'total_amount';
    totalInput.id = 'total_amount';
    totalInput.value = '0.00';
    form.appendChild(totalInput);
    
    // Initialize totals
    updateTotals();
});
</script>
@endpush
@endsection