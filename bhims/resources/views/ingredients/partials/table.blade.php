<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th scope="col" class="relative px-6 py-3">
                    <span class="sr-only">Actions</span>
                </th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($ingredients as $ingredient)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                <span class="text-gray-600 font-medium">{{ strtoupper(substr($ingredient->name, 0, 2)) }}</span>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ $ingredient->name }}</div>
                                <div class="text-sm text-gray-500">{{ $ingredient->unit_of_measure }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                            {{ $ingredient->category->name }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ number_format($ingredient->current_stock, 2) }}</div>
                        <div class="text-sm text-gray-500">Min: {{ number_format($ingredient->minimum_stock, 2) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ config('settings.currency_symbol') }}{{ number_format($ingredient->unit_price, 2) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($ingredient->current_stock <= 0)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                Out of Stock
                            </span>
                        @elseif($ingredient->current_stock < $ingredient->minimum_stock)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                Low Stock
                            </span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                In Stock
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end space-x-2">
                            <a href="{{ route('ingredients.show', $ingredient) }}" class="text-blue-600 hover:text-blue-900" title="View Details">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>
                            <a href="{{ route('ingredients.adjust-stock', $ingredient) }}" class="text-yellow-600 hover:text-yellow-900" title="Adjust Stock">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </a>
                            <a href="{{ route('ingredients.edit', $ingredient) }}" class="text-indigo-600 hover:text-indigo-900" title="Edit">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                            <button @click="$dispatch('open-delete-modal', { id: '{{ $ingredient->id }}', name: '{{ $ingredient->name }}' })" class="text-red-600 hover:text-red-900" title="Delete">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                        @if(isset($isLowStockPage) && $isLowStockPage)
                            No low-stock ingredients found. Great job!
                        @else
                            No ingredients found. Start by adding your first ingredient.
                        @endif
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
