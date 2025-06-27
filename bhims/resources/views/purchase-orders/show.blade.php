@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white dark:bg-gray-800">
                <!-- Header with PO Number and Status -->
                <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">
                            Purchase Order #{{ $purchaseOrder->po_number }}
                        </h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            Created on {{ $purchaseOrder->created_at->format('M d, Y') }} by {{ $purchaseOrder->user->name }}
                        </p>
                    </div>
                    <div class="mt-4 md:mt-0">
                        @php
                            $statusClasses = [
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'received' => 'bg-green-100 text-green-800',
                                'partially_received' => 'bg-blue-100 text-blue-800',
                                'cancelled' => 'bg-red-100 text-red-800',
                            ];
                            $statusLabels = [
                                'pending' => 'Pending',
                                'received' => 'Received',
                                'partially_received' => 'Partially Received',
                                'cancelled' => 'Cancelled',
                            ];
                        @endphp
                        <span class="px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-full {{ $statusClasses[$purchaseOrder->status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ $statusLabels[$purchaseOrder->status] ?? $purchaseOrder->status }}
                        </span>
                    </div>
                </div>

                <!-- Order Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-3">Supplier Information</h3>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <p class="font-medium text-gray-900 dark:text-white">{{ $purchaseOrder->supplier->name }}</p>
                            @if($purchaseOrder->supplier->email)
                                <p class="text-gray-600 dark:text-gray-300">{{ $purchaseOrder->supplier->email }}</p>
                            @endif
                            @if($purchaseOrder->supplier->phone)
                                <p class="text-gray-600 dark:text-gray-300">{{ $purchaseOrder->supplier->phone }}</p>
                            @endif
                            @if($purchaseOrder->supplier->address)
                                <p class="mt-2 text-gray-600 dark:text-gray-300 whitespace-pre-line">{{ $purchaseOrder->supplier->address }}</p>
                            @endif
                        </div>
                    </div>
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-3">Order Information</h3>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">PO Number</p>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $purchaseOrder->po_number }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Order Date</p>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $purchaseOrder->order_date->format('M d, Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Expected Delivery</p>
                                    <p class="font-medium text-gray-900 dark:text-white">
                                        {{ $purchaseOrder->expected_delivery_date->format('M d, Y') }}
                                        @if($purchaseOrder->expected_delivery_date->isPast() && $purchaseOrder->status !== 'received' && $purchaseOrder->status !== 'cancelled')
                                            <span class="text-xs text-red-500 ml-1">(Overdue)</span>
                                        @endif
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Status</p>
                                    <p class="font-medium">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClasses[$purchaseOrder->status] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ $statusLabels[$purchaseOrder->status] ?? $purchaseOrder->status }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg mb-8">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Order Summary</h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Items</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $itemCount }}</p>
                        </div>
                        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Quantity</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $totalItems }}</p>
                        </div>
                        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Received</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $receivedItems }}</p>
                        </div>
                        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Order Total</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">₹{{ number_format($purchaseOrder->total_amount, 2) }}</p>
                        </div>
                    </div>
                    
                    @if($purchaseOrder->status !== 'cancelled' && $purchaseOrder->status !== 'received')
                    <div class="mt-6">
                        <div class="flex justify-between text-sm text-gray-600 dark:text-gray-300 mb-1">
                            <span>Order Progress</span>
                            <span>{{ $completionPercentage }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-600">
                            <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $completionPercentage }}%"></div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Order Items -->
                <div class="mb-8">
                    <div class="flex justify-between items-center mb-3">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Order Items</h3>
                        <span class="text-sm text-gray-500 dark:text-gray-400">{{ $itemCount }} {{ Str::plural('item', $itemCount) }}</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Item</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Stock Info</th>
                                    <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ordered</th>
                                    <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Received</th>
                                    <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Unit Price</th>
                                    <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($purchaseOrder->items as $item)
                                @php
                                    $ingredient = $item->ingredient;
                                    $receivedQty = $item->received_quantity ?? 0;
                                    $pendingQty = max(0, $item->quantity - $receivedQty);
                                    $receivedPercentage = $item->quantity > 0 ? min(100, round(($receivedQty / $item->quantity) * 100)) : 0;
                                    $isLowStock = $ingredient->current_stock <= $ingredient->minimum_stock;
                                @endphp
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-4 py-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $ingredient->name }}
                                            @if($isLowStock)
                                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                    Low Stock
                                                </span>
                                            @endif
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $ingredient->unit_of_measure }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="text-sm text-gray-900 dark:text-white">
                                            <span class="font-medium">{{ $ingredient->current_stock }}</span>
                                            <span class="text-gray-500 dark:text-gray-400">/{{ $ingredient->minimum_stock }} {{ $ingredient->unit_of_measure }}</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-1.5 dark:bg-gray-600 mt-1">
                                            @php
                                                $stockPercentage = $ingredient->minimum_stock > 0 
                                                    ? min(100, ($ingredient->current_stock / $ingredient->minimum_stock) * 100)
                                                    : 100;
                                                $barColor = $isLowStock ? 'bg-red-500' : 'bg-green-500';
                                            @endphp
                                            <div class="h-1.5 rounded-full {{ $barColor }}" style="width: {{ $stockPercentage }}%"></div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-right text-gray-900 dark:text-white">
                                        {{ $item->quantity }} {{ $ingredient->unit_of_measure }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <div class="flex items-center justify-end">
                                            <span class="text-sm font-medium {{ $receivedQty < $item->quantity ? 'text-yellow-600 dark:text-yellow-400' : 'text-green-600 dark:text-green-400' }}">
                                                {{ $receivedQty }}
                                            </span>
                                            <span class="mx-1 text-gray-400">/</span>
                                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $item->quantity }} {{ $ingredient->unit_of_measure }}
                                            </span>
                                        </div>
                                        @if($item->quantity > 0)
                                        <div class="w-full bg-gray-200 rounded-full h-1.5 dark:bg-gray-600 mt-1">
                                            <div class="h-1.5 rounded-full {{ $receivedPercentage < 100 ? 'bg-yellow-500' : 'bg-green-500' }}" style="width: {{ $receivedPercentage }}%"></div>
                                        </div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-right text-gray-500 dark:text-gray-300">
                                        LKR {{ number_format($item->unit_price, 2) }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-right font-medium text-gray-900 dark:text-white">
                                        LKR {{ number_format($item->total_price, 2) }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <td colspan="5" class="px-4 py-3 text-right text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Subtotal
                                    </td>
                                    <td class="px-4 py-3 text-right text-sm font-medium text-gray-900 dark:text-white">
                                        LKR {{ number_format($purchaseOrder->total_amount, 2) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="px-4 py-3 text-right text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Tax (GST @ 18%)
                                    </td>
                                    <td class="px-4 py-3 text-right text-sm font-medium text-gray-900 dark:text-white">
                                        LKR {{ number_format($purchaseOrder->total_amount * 0.18, 2) }}
                                    </td>
                                </tr>
                                <tr class="bg-white dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
                                    <td colspan="5" class="px-4 py-3 text-right text-base font-bold text-gray-900 dark:text-white">
                                        Total Amount
                                    </td>
                                    <td class="px-4 py-3 text-right text-base font-bold text-gray-900 dark:text-white">
                                        LKR {{ number_format($purchaseOrder->total_amount * 1.18, 2) }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- Notes -->
                @if($purchaseOrder->notes)
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-3">Notes</h3>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $purchaseOrder->notes }}</p>
                        </div>
                    </div>
                @endif

                <!-- Status Update Form -->
                @if(in_array($purchaseOrder->status, ['pending', 'partially_received']))
                    <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <div class="md:flex md:items-center md:justify-between mb-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Update Order Status</h3>
                            <span class="mt-2 md:mt-0 inline-flex rounded-md shadow-sm">
                                <button type="button" id="receiveAllBtn" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Receive All Items
                                </button>
                            </span>
                        </div>
                        
                        <form action="{{ route('purchase-orders.update-status', $purchaseOrder) }}" method="POST" class="space-y-4" id="statusForm">
                            @csrf
                            <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
                                <div class="px-4 py-5 sm:p-6">
                                    <div class="grid grid-cols-1 gap-6">
                                        <div class="col-span-6 sm:col-span-3">
                                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                                            <select name="status" id="status" class="mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                                <option value="received" {{ $purchaseOrder->status === 'received' ? 'selected' : '' }}>Fully Received</option>
                                                <option value="partially_received" {{ $purchaseOrder->status === 'partially_received' ? 'selected' : '' }}>Partially Received</option>
                                                <option value="cancelled">Cancel Order</option>
                                            </select>
                                        </div>

                                        <div id="receivedItemsSection" class="hidden">
                                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Received Quantities</h4>
                                            <div class="overflow-x-auto">
                                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                                        <tr>
                                                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Item</th>
                                                            <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ordered</th>
                                                            <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Received</th>
                                                            <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">This Delivery</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-600">
                                                        @foreach($purchaseOrder->items as $item)
                                                        @php
                                                            $receivedQty = $item->received_quantity ?? 0;
                                                            $maxQty = $item->quantity - $receivedQty;
                                                        @endphp
                                                        <tr>
                                                            <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                                {{ $item->ingredient->name }}
                                                                <input type="hidden" name="items[{{ $item->id }}][ingredient_id]" value="{{ $item->ingredient_id }}">
                                                            </td>
                                                            <td class="px-3 py-2 whitespace-nowrap text-right text-sm text-gray-500 dark:text-gray-300">
                                                                {{ $item->quantity }} {{ $item->ingredient->unit_of_measure }}
                                                            </td>
                                                            <td class="px-3 py-2 whitespace-nowrap text-right text-sm text-gray-500 dark:text-gray-300">
                                                                {{ $receivedQty }} {{ $item->ingredient->unit_of_measure }}
                                                            </td>
                                                            <td class="px-3 py-2 whitespace-nowrap">
                                                                <div class="flex items-center justify-end">
                                                                    <input type="number" 
                                                                           name="items[{{ $item->id }}][received_quantity]" 
                                                                           class="block w-24 text-right px-2 py-1 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                                                           min="0" 
                                                                           max="{{ $maxQty }}" 
                                                                           step="0.01"
                                                                           value="{{ $maxQty }}">
                                                                    <span class="ml-2 text-sm text-gray-500 dark:text-gray-300">{{ $item->ingredient->unit_of_measure }}</span>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <div>
                                            <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notes</label>
                                            <div class="mt-1">
                                                <textarea id="notes" name="notes" rows="3" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-1 block w-full sm:text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md" placeholder="Add any notes about this status update">{{ old('notes', $purchaseOrder->notes) }}</textarea>
                                            </div>
                                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                                Brief description of any issues or additional information about this delivery.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 text-right sm:px-6 rounded-b-lg">
                                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        Update Status
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700 flex justify-between">
                    <a href="{{ route('purchase-orders.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md shadow-sm text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Back to List
                    </a>
                    
                    <div class="space-x-3">
                        <a href="#" onclick="window.print()" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md shadow-sm text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z" clip-rule="evenodd" />
                            </svg>
                            Print
                        </a>
                        
                        @if($purchaseOrder->status === 'pending')
                            <a href="#" 
                               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                               onclick="event.preventDefault(); if(confirm('Are you sure you want to delete this purchase order?')) { document.getElementById('delete-form').submit(); }">
                                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                Delete
                            </a>
                            <form id="delete-form" action="{{ route('purchase-orders.destroy', $purchaseOrder) }}" method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const statusSelect = document.getElementById('status');
        const receivedItemsSection = document.getElementById('receivedItemsSection');
        const receiveAllBtn = document.getElementById('receiveAllBtn');
        const statusForm = document.getElementById('statusForm');
        const receivedQuantityInputs = document.querySelectorAll('input[name^="items["]');
        
        // Toggle received items section based on status
        function toggleReceivedItemsSection() {
            if (statusSelect.value === 'received' || statusSelect.value === 'partially_received') {
                receivedItemsSection.classList.remove('hidden');
            } else {
                receivedItemsSection.classList.add('hidden');
            }
        }
        
        // Set all received quantities to max
        function setAllToMax() {
            receivedQuantityInputs.forEach(input => {
                const maxQty = parseFloat(input.max);
                input.value = maxQty.toFixed(2);
            });
        }
        
        // Handle status change
        statusSelect.addEventListener('change', toggleReceivedItemsSection);
        
        // Handle receive all button click
        if (receiveAllBtn) {
            receiveAllBtn.addEventListener('click', function(e) {
                e.preventDefault();
                statusSelect.value = 'received';
                toggleReceivedItemsSection();
                setAllToMax();
                // Smooth scroll to form
                statusForm.scrollIntoView({ behavior: 'smooth' });
            });
        }
        
        // Initialize the form state
        toggleReceivedItemsSection();
        
        // Form validation
        if (statusForm) {
            statusForm.addEventListener('submit', function(e) {
                const status = statusSelect.value;
                let isValid = true;
                
                if (status === 'received' || status === 'partially_received') {
                    let totalReceived = 0;
                    receivedQuantityInputs.forEach(input => {
                        totalReceived += parseFloat(input.value) || 0;
                    });
                    
                    if (totalReceived <= 0) {
                        e.preventDefault();
                        alert('Please enter received quantities for at least one item.');
                        isValid = false;
                    }
                }
                
                if (!isValid) {
                    e.preventDefault();
                }
            });
        }
        
        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-tooltip]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush

<style>
    @media print {
        body * {
            visibility: hidden;
        }
        .print-content, .print-content * {
            visibility: visible;
        }
        .print-content {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        .no-print {
            display: none !important;
        }
    }
</style>
@endsection