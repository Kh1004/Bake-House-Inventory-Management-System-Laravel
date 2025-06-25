@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">{{ $recipe->name }}</h1>
                <p class="text-gray-600 mt-2">{{ $recipe->description }}</p>
            </div>
            <div class="mt-4 md:mt-0 flex space-x-3">
                <a href="{{ route('recipes.edit', $recipe) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-edit mr-2"></i> Edit Recipe
                </a>
                <a href="{{ route('recipes.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Recipes
                </a>
            </div>
        </div>

        <!-- Recipe Stats -->
        <div class="bg-white shadow rounded-lg overflow-hidden mb-8">
            <div class="px-6 py-5 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Recipe Overview</h3>
            </div>
            <div class="px-6 py-5">
                <dl class="grid grid-cols-1 gap-5 sm:grid-cols-3">
                    <div class="px-4 py-5 bg-white shadow rounded-lg overflow-hidden sm:p-6">
                        <dt class="text-sm font-medium text-gray-500 truncate">Serving Size</dt>
                        <dd class="mt-1 text-2xl font-semibold text-gray-900">
                            {{ number_format($recipe->serving_size, 1) }} {{ $recipe->serving_size == 1 ? 'Serving' : 'Servings' }}
                        </dd>
                    </div>
                    <div class="px-4 py-5 bg-white shadow rounded-lg overflow-hidden sm:p-6">
                        <dt class="text-sm font-medium text-gray-500 truncate">Cost per Serving</dt>
                        <dd class="mt-1 text-2xl font-semibold text-gray-900">
                            ${{ number_format($recipe->cost_per_serving, 2) }}
                        </dd>
                    </div>
                    <div class="px-4 py-5 bg-white shadow rounded-lg overflow-hidden sm:p-6">
                        <dt class="text-sm font-medium text-gray-500 truncate">Selling Price</dt>
                        <dd class="mt-1 text-2xl font-semibold text-gray-900">
                            ${{ number_format($recipe->selling_price, 2) }}
                        </dd>
                    </div>
                </dl>
                <div class="mt-6 px-4 py-5 bg-blue-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-100 rounded-md p-3">
                            <i class="fas fa-chart-line text-blue-600 text-2xl"></i>
                        </div>
                        <div class="ml-5">
                            <h4 class="text-lg font-medium text-gray-900">Profit Analysis</h4>
                            <div class="mt-1 grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500">Profit per Serving</p>
                                    <p class="text-lg font-semibold text-green-600">
                                        ${{ number_format($recipe->selling_price - $recipe->cost_per_serving, 2) }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Profit Margin</p>
                                    <p class="text-lg font-semibold text-green-600">
                                        {{ $recipe->cost_per_serving > 0 ? number_format((($recipe->selling_price - $recipe->cost_per_serving) / $recipe->cost_per_serving) * 100, 1) : 'N/A' }}%
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Ingredients Section -->
            <div class="lg:col-span-1">
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Ingredients</h3>
                    </div>
                    <div class="px-6 py-4">
                        <ul class="divide-y divide-gray-200">
                            @forelse($recipe->ingredients as $ingredient)
                                <li class="py-3">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <span class="font-medium text-gray-900">{{ $ingredient->name }}</span>
                                        </div>
                                        <div class="ml-4 text-right">
                                            <span class="text-sm text-gray-900">
                                                {{ number_format($ingredient->pivot->quantity, 2) }} {{ $ingredient->pivot->unit_of_measure }}
                                            </span>
                                            @if($ingredient->pivot->notes)
                                                <p class="text-xs text-gray-500 mt-1">{{ $ingredient->pivot->notes }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </li>
                            @empty
                                <li class="py-3 text-center text-gray-500">No ingredients added yet.</li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="px-6 py-4 bg-gray-50 text-right">
                        <span class="text-sm text-gray-500">
                            {{ $recipe->ingredients->count() }} {{ Str::plural('ingredient', $recipe->ingredients->count()) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Instructions Section -->
            <div class="lg:col-span-2">
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Instructions</h3>
                    </div>
                    <div class="px-6 py-4">
                        @if($recipe->instructions)
                            <div class="prose max-w-none">
                                {!! nl2br(e($recipe->instructions)) !!}
                            </div>
                        @else
                            <p class="text-gray-500 italic">No instructions provided for this recipe.</p>
                        @endif
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="mt-6 bg-white shadow rounded-lg overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Additional Information</h3>
                    </div>
                    <div class="px-6 py-4">
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Status</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $recipe->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $recipe->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Created</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ $recipe->created_at->format('M d, Y') }}
                                </dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ $recipe->updated_at->format('M d, Y') }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
            <div class="sm:flex sm:items-start">
                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                    <i class="fas fa-exclamation text-red-600"></i>
                </div>
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                        Delete Recipe
                    </h3>
                    <div class="mt-2">
                        <p class="text-sm text-gray-500">
                            Are you sure you want to delete this recipe? This action cannot be undone.
                        </p>
                    </div>
                </div>
            </div>
            <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                <form action="{{ route('recipes.destroy', $recipe) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Delete
                    </button>
                </form>
                <button type="button" onclick="document.getElementById('deleteModal').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:w-auto sm:text-sm">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function confirmDelete() {
        document.getElementById('deleteModal').classList.remove('hidden');
    }
</script>
@endpush

@endsection