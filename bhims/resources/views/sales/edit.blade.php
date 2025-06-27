@extends('layouts.app')

@section('header', 'Edit Sale #' . $sale->invoice_number)

@section('content')
<div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
    <div class="px-4 py-5 sm:p-6">
        <form action="{{ route('sales.update', $sale->id) }}" method="POST" id="saleForm">
            @csrf
            @method('PUT')
            
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
                            <option value="{{ $customer->id }}" {{ $sale->customer_id == $customer->id ? 'selected' : '' }}>{{ $customer->name }}</option>
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
                        <option value="paid" {{ $sale->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="pending" {{ $sale->payment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="partial" {{ $sale->payment_status == 'partial' ? 'selected' : '' }}>Partial</option>
                    </select>
                </div>

                <!-- Payment Method (shown conditionally) -->
                <div id="payment_method_container" class="{{ in_array($sale->payment_status, ['paid', 'partial']) ? '' : 'hidden' }}">
                    <label for="payment_method" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Payment Method <span class="text-red-500">*</span>
                    </label>
                    <select name="payment_method" id="payment_method"
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="">Select payment method</option>
                        <option value="cash" {{ $sale->payment_method == 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="card" {{ $sale->payment_method == 'card' ? 'selected' : '' }}>Credit/Debit Card</option>
                        <option value="bank_transfer" {{ $sale->payment_method == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                        <option value="other" {{ $sale->payment_method == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <!-- Notes -->
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Notes
                    </label>
                    <input type="text" name="notes" id="notes" value="{{ old('notes', $sale->notes) }}"
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
                            <span id="subtotal">LKR {{ number_format($sale->subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-base font-medium text-gray-900 dark:text-white mb-2">
                            <span>Tax (0%):</span>
                            <span id="tax">LKR {{ number_format($sale->tax_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-lg font-bold text-gray-900 dark:text-white pt-2 border-t border-gray-200 dark:border-gray-600">
                            <span>Total:</span>
                            <span id="total">LKR {{ number_format($sale->total, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="mt-8 flex justify-end">
                <a href="{{ route('sales.show', $sale->id) }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:bg-gray-600">
                    Cancel
                </a>
                <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Update Sale
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
            <input type="hidden" name="items[__INDEX__][id]" value="">
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
                <input type="number" name="items[__INDEX__][unit_price]" step="0.01" min="0" value="0" required
                       class="price-input flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
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
    // Toggle payment method field based on payment status
    const paymentStatus = document.getElementById('payment_status');
    const paymentMethodContainer = document.getElementById('payment_method_container');
    const paymentMethodSelect = document.getElementById('payment_method');
    
    function updatePaymentMethodVisibility() {
        if (paymentStatus.value === 'paid' || paymentStatus.value === 'partial') {
            paymentMethodContainer.classList.remove('hidden');
            paymentMethodSelect.required = true;
        } else {
            paymentMethodContainer.classList.add('hidden');
            paymentMethodSelect.required = false;
            paymentMethodSelect.value = '';
        }
    }
    
    paymentStatus.addEventListener('change', updatePaymentMethodVisibility);
    updatePaymentMethodVisibility();
    
    let productIndex = 0;
    const productRows = document.getElementById('productRows');
    const productRowTemplate = document.getElementById('productRowTemplate');
    const addProductBtn = document.getElementById('addProduct');
    const form = document.getElementById('saleForm');

    // Initialize with existing items
    @foreach($sale->items as $item)
        addProductRow(
            '{{ $item->id }}', 
            '{{ $item->product_id }}', 
            '{{ $item->quantity }}', 
            '{{ $item->unit_price }}',
            '{{ $item->product->name }} (LKR {{ number_format($item->product->selling_price, 2) }})'
        );
    @endforeach

    // If no items, add one empty row
    if (document.querySelectorAll('.product-row').length === 0) {
        addProductRow();
    }

    // Add new product row
    addProductBtn.addEventListener('click', function() {
        addProductRow();
    });

    // Add product row function
    function addProductRow(id = '', productId = '', quantity = 1, unitPrice = '', productLabel = '') {
        const newRow = productRowTemplate.content.cloneNode(true);
        const newIndex = productIndex++;
        
        // Update all elements with the new index
        newRow.querySelectorAll('[name^="items["]').forEach(el => {
            el.name = el.name.replace('__INDEX__', newIndex);
            
            // Set values if provided
            if (el.name.includes('[product_id]') && productId) {
                el.value = productId;
            } else if (el.name.includes('[quantity]') && quantity) {
                el.value = quantity;
            } else if (el.name.includes('[unit_price]') && unitPrice) {
                el.value = unitPrice;
            } else if (el.name.includes('[id]') && id) {
                el.value = id;
            }
        });
        
        // Set product name in select if provided
        if (productLabel && productId) {
            const select = newRow.querySelector('.product-select');
            const option = document.createElement('option');
            option.value = productId;
            option.textContent = productLabel;
            option.selected = true;
            select.appendChild(option);
            
            // Trigger change to update price
            select.dispatchEvent(new Event('change'));
        }
        
        // Add event listeners
        const rowElement = newRow.querySelector('.product-row');
        productRows.appendChild(rowElement);
        
        // Initialize the row
        initProductRow(rowElement);
        
        // Update totals
        updateTotals();
    }

    // Initialize a product row
    function initProductRow(row) {
        const select = row.querySelector('.product-select');
        const quantityInput = row.querySelector('.quantity-input');
        const priceInput = row.querySelector('.price-input');
        
        // Set default quantity to 1 if empty
        if (quantityInput && !quantityInput.value) {
            quantityInput.value = '1';
        }
        
        // Set default price if empty
        if (priceInput && !priceInput.value) {
            priceInput.value = '0.00';
        }
        
        // Add event listeners
        if (select) {
            select.addEventListener('change', function() {
                updatePriceInput(this);
                updateRowTotal(this.closest('.product-row'));
                updateTotals();
            });
        }
        
        if (quantityInput) {
            quantityInput.addEventListener('input', function() {
                updateRowTotal(this.closest('.product-row'));
                updateTotals();
            });
        }
        
        // Add remove button handler
        const removeBtn = row.querySelector('.remove-product');
        if (removeBtn) {
            removeBtn.addEventListener('click', function() {
                const row = this.closest('.product-row');
                row.remove();
                updateTotals();
                
                // If no rows left, add one
                if (document.querySelectorAll('.product-row').length === 0) {
                    addProductRow();
                }
            });
        }
        
        // Update price input if product is selected
        if (select && select.value) {
            updatePriceInput(select);
        }
        
        // Update row total
        updateRowTotal(row);
    }
    
    // Update price input based on selected product
    function updatePriceInput(select) {
        const row = select.closest('.product-row');
        const priceInput = row.querySelector('.price-input');
        const selectedOption = select.options[select.selectedIndex];
        
        if (selectedOption && selectedOption.dataset.price) {
            priceInput.value = selectedOption.dataset.price;
        }
    }
    
    // Update row total
    function updateRowTotal(row) {
        const quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
        const price = parseFloat(row.querySelector('.price-input').value) || 0;
        const total = quantity * price;
        
        row.querySelector('.item-total').textContent = 'LKR ' + total.toFixed(2);
    }
    
    // Update all totals
    function updateTotals() {
        let subtotal = 0;
        
        document.querySelectorAll('.product-row').forEach(row => {
            const quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
            const price = parseFloat(row.querySelector('.price-input').value) || 0;
            subtotal += quantity * price;
        });
        
        const tax = 0; // You can add tax calculation here if needed
        const total = subtotal + tax;
        
        document.getElementById('subtotal').textContent = 'LKR ' + subtotal.toFixed(2);
        document.getElementById('tax').textContent = 'LKR ' + tax.toFixed(2);
        document.getElementById('total').textContent = 'LKR ' + total.toFixed(2);
    }
    
    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const submitButton = form.querySelector('button[type="submit"]');
        submitButton.disabled = true;
        
        // Clear previous errors
        document.querySelectorAll('.text-red-500.text-xs').forEach(el => el.remove());
        document.querySelectorAll('input, select').forEach(el => {
            el.classList.remove('border-red-500');
            const parent = el.closest('div');
            if (parent) {
                parent.classList.remove('has-error');
            }
        });
        
        // Update form data with the latest totals
        const formData = new FormData(form);
        const data = {};
        
        // Convert FormData to object
        formData.forEach((value, key) => {
            // Handle array inputs (like items)
            if (key.includes('[') && key.includes(']')) {
                const matches = key.match(/(\w+)\[(\d+)\]\[(\w+)\]/);
                if (matches) {
                    const [_, name, index, field] = matches;
                    if (!data[name]) data[name] = [];
                    if (!data[name][index]) data[name][index] = {};
                    data[name][index][field] = value;
                    
                    // Add total field if this is a unit_price or quantity field
                    if (field === 'unit_price' || field === 'quantity') {
                        const row = document.querySelector(`[name="items[${index}][${field}]"]`).closest('.product-row');
                        if (row) {
                            const quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
                            const price = parseFloat(row.querySelector('.price-input').value) || 0;
                            const total = quantity * price;
                            data[name][index]['total'] = total.toFixed(2);
                        }
                    }
                } else {
                    data[key] = value;
                }
            } else {
                data[key] = value;
            }
        });
        
        // Add CSRF token
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-HTTP-Method-Override': 'PUT'
            },
            body: JSON.stringify(data)
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => {
                    throw err;
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.redirect) {
                window.location.href = data.redirect;
            } else {
                window.location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            
            if (error.errors) {
                // Display validation errors
                Object.entries(error.errors).forEach(([field, messages]) => {
                    // Handle nested fields like items.0.quantity
                    const fieldParts = field.split('.');
                    let element;
                    
                    if (fieldParts[0] === 'items' && fieldParts.length === 3) {
                        // Handle items array fields (e.g., items.0.quantity)
                        const index = fieldParts[1];
                        const itemField = fieldParts[2];
                        element = form.querySelector(`[name="items[${index}][${itemField}]"]`);
                    } else {
                        // Handle regular fields
                        element = form.querySelector(`[name="${field}"]`);
                    }
                    
                    if (element) {
                        element.classList.add('border-red-500');
                        const parent = element.closest('div');
                        if (parent) {
                            parent.classList.add('has-error');
                            const errorDiv = document.createElement('div');
                            errorDiv.className = 'text-red-500 text-xs mt-1';
                            errorDiv.textContent = Array.isArray(messages) ? messages[0] : messages;
                            parent.appendChild(errorDiv);
                        }
                    }
                });
                
                // Scroll to first error
                const firstError = document.querySelector('.has-error');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            } else {
                // Show generic error message
                alert(error.message || 'An error occurred while saving the sale. Please try again.');
            }
            
            submitButton.disabled = false;
        });
    });
});
</script>
@endpush
@endsection