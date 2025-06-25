@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Ingredient Details Card -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6 bg-white dark:bg-gray-800">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div class="mb-4 md:mb-0">
                        <div class="flex items-center space-x-2 mb-2">
                            <a href="{{ route('ingredients.index') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                                </svg>
                            </a>
                            <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">
                                {{ $ingredient->name }}
                            </h2>
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $ingredient->description ?? 'No description available' }}
                        </p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('ingredients.adjust-stock', $ingredient) }}" 
                           class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            Adjust Stock
                        </a>
                        <a href="{{ route('ingredients.edit', $ingredient) }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-800 dark:text-white uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:bg-gray-300 dark:focus:bg-gray-600 active:bg-gray-400 dark:active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            Edit
                        </a>
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Current Stock</p>
                        <p class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">
                            {{ number_format($ingredient->current_stock, 2) }} {{ $ingredient->unit_of_measure }}
                        </p>
                        @if($ingredient->minimum_stock > 0)
                            @if($ingredient->current_stock <= $ingredient->minimum_stock)
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">
                                    Below minimum stock ({{ $ingredient->minimum_stock }} {{ $ingredient->unit_of_measure }})
                                </p>
                            @else
                                <p class="mt-1 text-sm text-green-600 dark:text-green-400">
                                    Above minimum stock ({{ $ingredient->minimum_stock }} {{ $ingredient->unit_of_measure }})
                                </p>
                            @endif
                        @endif
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Unit of Measure</p>
                        <p class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">
                            {{ $ingredient->unit_of_measure }}
                        </p>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Unit Price</p>
                        <p class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">
                            {{ number_format($ingredient->unit_price, 2) }} {{ config('app.currency', '$') }}
                        </p>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</p>
                        <span class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $ingredient->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                            {{ $ingredient->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    @if($ingredient->category)
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Category</p>
                        <p class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $ingredient->category->name }}
                        </p>
                    </div>
                    @endif
                    
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Supplier</p>
                        @if($ingredient->supplier)
                            <div class="mt-1">
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ $ingredient->supplier->name }}
                                </p>
                                @if($ingredient->supplier->contact_person || $ingredient->supplier->phone)
                                <div class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                                    @if($ingredient->supplier->contact_person)
                                        <p>Contact: {{ $ingredient->supplier->contact_person }}</p>
                                    @endif
                                    @if($ingredient->supplier->phone)
                                        <p>Phone: {{ $ingredient->supplier->phone }}</p>
                                    @endif
                                    @if($ingredient->supplier->email)
                                        <p>Email: <a href="mailto:{{ $ingredient->supplier->email }}" class="text-blue-600 dark:text-blue-400 hover:underline">{{ $ingredient->supplier->email }}</a></p>
                                    @endif
                                </div>
                                @endif
                            </div>
                        @else
                            <p class="mt-1 text-gray-500 dark:text-gray-400">No supplier assigned</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Stock Movements Card -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white dark:bg-gray-800">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Stock Movement History</h3>
                
                @if($stockMovements->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Type</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Quantity</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Notes</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">User</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($stockMovements as $movement)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                        {{ $movement->created_at->format('M d, Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $typeClasses = [
                                                'addition' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                                'removal' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                                'adjustment' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                                'initial' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
                                            ][$movement->movement_type] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200';
                                        @endphp
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $typeClasses }}">
                                            {{ ucfirst($movement->movement_type) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                        {{ number_format($movement->quantity, 2) }} {{ $ingredient->unit_of_measure }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-200">
                                        {{ $movement->notes }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                        {{ $movement->user->name ?? 'System' }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $stockMovements->links() }}
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No stock movements</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Get started by adjusting the stock level.
                        </p>
                        <div class="mt-6">
                            <a href="{{ route('ingredients.adjust-stock', $ingredient) }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                                </svg>
                                Adjust Stock
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
