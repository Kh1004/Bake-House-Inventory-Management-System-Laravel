@forelse($recipes as $recipe)
<tr class="hover:bg-gray-50 transition-colors duration-200 transform hover:scale-[1.002]" id="recipe-row-{{ $recipe->id }}">
    <td class="px-6 py-4 whitespace-nowrap">
        <div class="flex items-center">
            <div class="text-sm font-medium text-gray-900">
                <a href="{{ route('recipes.show', $recipe) }}" class="text-blue-600 hover:text-blue-800 hover:underline transition-colors duration-200">
                    {{ $recipe->name }}
                </a>
            </div>
        </div>
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
        {{ $recipe->serving_size }} {{ $recipe->serving_size > 1 ? 'servings' : 'serving' }}
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" data-price="{{ $recipe->cost_per_serving }}">
        {{ number_format($recipe->cost_per_serving, 2) }}
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" data-price="{{ $recipe->selling_price }}">
        {{ number_format($recipe->selling_price, 2) }}
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
        <div class="relative inline-block w-12 align-middle select-none">
            <button type="button" 
                    onclick="toggleRecipeStatus({{ $recipe->id }})" 
                    class="toggle-btn relative block w-12 h-6 rounded-full overflow-hidden transition-colors duration-200 {{ $recipe->is_active ? 'bg-blue-500' : 'bg-gray-300' }} cursor-pointer"
                    title="{{ $recipe->is_active ? 'Deactivate' : 'Activate' }}"
                    id="toggle-{{ $recipe->id }}">
                <span class="absolute left-0 top-0 w-6 h-6 rounded-full bg-white shadow-md transform transition-transform duration-200 {{ $recipe->is_active ? 'translate-x-6' : 'translate-x-0' }}">
                    <span class="absolute inset-0 flex items-center justify-center text-[10px] font-bold {{ $recipe->is_active ? 'text-blue-500' : 'text-gray-400' }}" id="toggle-icon-{{ $recipe->id }}">
                        {{ $recipe->is_active ? '✓' : '✕' }}
                    </span>
                </span>
            </button>
        </div>
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
        <div class="flex justify-end space-x-2">
            <a href="{{ route('recipes.edit', $recipe) }}" class="text-indigo-600 hover:text-indigo-900 mr-3" title="Edit">
                <i class="fas fa-edit"></i>
            </a>
            <form action="{{ route('recipes.destroy', $recipe) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this recipe? This action cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:text-red-900" title="Delete">
                    <i class="fas fa-trash"></i>
                </button>
            </form>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
        No recipes found. <a href="{{ route('recipes.create') }}" class="text-blue-600 hover:underline">Create one now</a>.
    </td>
</tr>
@endforelse