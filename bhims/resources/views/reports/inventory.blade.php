@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Inventory Report</h1>
        <div class="text-lg font-semibold">
            Total Stock Value: <span class="text-blue-600">LKR {{ number_format($totalStockValue, 2) }}</span>
        </div>
    </div>

    <!-- Low Stock Alert -->
    @if($lowStockItems->isNotEmpty())
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-yellow-800">
                    {{ $lowStockItems->count() }} {{ Str::plural('item', $lowStockItems->count()) }} below minimum stock level
                </h3>
                <div class="mt-2 text-sm text-yellow-700">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach($lowStockItems as $item)
                            <li>{{ $item->name }} ({{ $item->current_stock }} {{ $item->unit->abbreviation ?? '' }} remaining, min: {{ $item->minimum_stock }})</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Inventory Table -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
        <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
            <div class="flex justify-between items-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Current Inventory
                </h3>
                <form action="{{ route('reports.inventory') }}" method="GET" class="flex items-center space-x-4">
                    <div>
                        <select name="sort_by" onchange="this.form.submit()" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                            <option value="name" {{ $sortBy === 'name' ? 'selected' : '' }}>Name</option>
                            <option value="current_stock" {{ $sortBy === 'current_stock' ? 'selected' : '' }}>Stock Level</option>
                            <option value="unit_price" {{ $sortBy === 'unit_price' ? 'selected' : '' }}>Unit Price</option>
                        </select>
                    </div>
                    <input type="hidden" name="sort_order" value="{{ $sortOrder === 'asc' ? 'desc' : 'asc' }}">
                    <button type="button" onclick="this.form.sort_order.value = '{{ $sortOrder === 'asc' ? 'desc' : 'asc' }}'; this.form.submit()" class="text-gray-500 hover:text-gray-700">
                        @if($sortOrder === 'asc')
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                            </svg>
                        @else
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 011.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        @endif
                    </button>
                </form>
            </div>
        </div>
        <div class="flex flex-col">
            <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                    <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-b-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Ingredient
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Current Stock
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Min. Stock
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Unit Price
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Stock Value
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        In/Out
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($ingredients as $ingredient)
                                    <tr class="{{ $ingredient->current_stock <= $ingredient->minimum_stock ? 'bg-red-50' : '' }}">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $ingredient->name }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        {{ $ingredient->category->name ?? 'Uncategorized' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm {{ $ingredient->current_stock <= $ingredient->minimum_stock ? 'text-red-600 font-semibold' : 'text-gray-900' }}">
                                            {{ number_format($ingredient->current_stock, 2) }} {{ $ingredient->unit_of_measure ?? '' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500">
                                            {{ $ingredient->minimum_stock }} {{ $ingredient->unit_of_measure ?? '' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500">
                                            LKR {{ number_format($ingredient->unit_price, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            LKR {{ number_format($ingredient->current_stock * $ingredient->unit_price, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-xs">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                +{{ $ingredient->total_in ?? 0 }}
                                            </span>
                                            <span class="ml-1 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                -{{ $ingredient->total_out ?? 0 }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            No ingredients found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
            <div class="flex-1 flex justify-between sm:hidden">
                {{ $ingredients->links('pagination::simple-tailwind') }}
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Showing
                        <span class="font-medium">{{ $ingredients->firstItem() }}</span>
                        to
                        <span class="font-medium">{{ $ingredients->lastItem() }}</span>
                        of
                        <span class="font-medium">{{ $ingredients->total() }}</span>
                        results
                    </p>
                </div>
                <div>
                    {{ $ingredients->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
