<!-- Sidebar Navigation -->
<div class="h-full flex flex-col">
    <!-- Logo -->
    <div class="flex items-center h-16 flex-shrink-0 px-4 bg-indigo-600">
        <a href="{{ route('dashboard') }}" class="flex items-center">
            <svg class="h-8 w-auto text-white" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M13 10V3L4 14H11V21L20 10H13Z" fill="currentColor" />
            </svg>
            <span class="ml-2 text-white text-xl font-semibold">BHIMS</span>
        </a>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-2 py-4 space-y-1 overflow-y-auto">
        @php
            $currentRoute = request()->route() ? request()->route()->getName() : '';
            $isActive = function ($route) use ($currentRoute) {
                if (is_array($route)) {
                    return in_array($currentRoute, $route) 
                        ? 'bg-indigo-50 dark:bg-gray-700 text-indigo-700 dark:text-white' 
                        : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700';
                }
                return $currentRoute === $route 
                    ? 'bg-indigo-50 dark:bg-gray-700 text-indigo-700 dark:text-white' 
                    : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700';
            };
        @endphp

        <!-- Dashboard -->
        <div x-data="{ open: {{ in_array($currentRoute, ['dashboard', 'dashboard.quick-actions', 'dashboard.recent-activities']) ? 'true' : 'false' }} }">
            <button @click="open = !open" 
                    class="group w-full flex items-center px-3 py-2 text-sm font-medium rounded-md {{ $isActive(['dashboard', 'dashboard.quick-actions', 'dashboard.recent-activities']) }} transition-colors duration-200">
                <svg class="mr-3 h-6 w-6 flex-shrink-0 {{ in_array($currentRoute, ['dashboard', 'dashboard.quick-actions', 'dashboard.recent-activities']) ? 'text-indigo-500 dark:text-indigo-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }}" 
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                {{ __('Dashboard') }}
                <svg :class="{'rotate-90': open}" class="ml-auto h-5 w-5 transform transition-transform duration-200 text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300" 
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            <div x-show="open" class="mt-1 space-y-1 pl-12">
                <a href="{{ route('dashboard') }}" 
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ $isActive('dashboard') }} transition-colors duration-200">
                    {{ __('Overview') }}
                </a>
                <a href="{{ route('dashboard.quick-actions') }}" 
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ $isActive('dashboard.quick-actions') }} transition-colors duration-200">
                    {{ __('Quick Actions') }}
                </a>
                <a href="{{ route('dashboard.recent-activities') }}" 
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ $isActive('dashboard.recent-activities') }} transition-colors duration-200">
                    {{ __('Recent Activities') }}
                </a>
            </div>
        </div>


        <!-- Ingredients -->
        <div x-data="{ open: {{ in_array($currentRoute, ['ingredients.index', 'ingredients.create', 'ingredients.edit', 'ingredients.low-stock']) ? 'true' : 'false' }} }">
            <button @click="open = !open" 
                    class="group w-full flex items-center px-3 py-2 text-sm font-medium rounded-md {{ $isActive(['ingredients.index', 'ingredients.create', 'ingredients.edit', 'ingredients.low-stock']) }} transition-colors duration-200">
                <svg class="mr-3 h-6 w-6 flex-shrink-0 {{ in_array($currentRoute, ['ingredients.index', 'ingredients.create', 'ingredients.edit', 'ingredients.low-stock']) ? 'text-indigo-500 dark:text-indigo-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }}" 
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                {{ __('Ingredients') }}
                <svg :class="{'rotate-90': open}" class="ml-auto h-5 w-5 transform transition-transform duration-200 text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300" 
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            <div x-show="open" class="mt-1 space-y-1 pl-12">
                <a href="{{ route('ingredients.index') }}" 
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ $isActive('ingredients.index') }} transition-colors duration-200">
                    {{ __('All Ingredients') }}
                </a>
                <a href="{{ route('ingredients.create') }}" 
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ $isActive('ingredients.create') }} transition-colors duration-200">
                    {{ __('Add New') }}
                </a>
                <a href="{{ route('ingredients.low-stock') }}" 
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ $isActive('ingredients.low-stock') }} transition-colors duration-200">
                    {{ __('Low Stock Alerts') }}
                </a>
            </div>
        </div>

        <!-- User Management -->
        @can('viewAny', App\Models\User::class)
        <div x-data="{ open: {{ in_array($currentRoute, ['users.index', 'users.create', 'users.edit']) ? 'true' : 'false' }} }">
            <button @click="open = !open" 
                    class="group w-full flex items-center px-3 py-2 text-sm font-medium rounded-md {{ $isActive(['users.index', 'users.create', 'users.edit']) }} transition-colors duration-200">
                <svg class="mr-3 h-6 w-6 flex-shrink-0 {{ in_array($currentRoute, ['users.index', 'users.create', 'users.edit']) ? 'text-indigo-500 dark:text-indigo-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }}" 
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0111.5-3M13 7a4 4 0 11-8 0 4 4 0 018 0zm6 12h.01M16 16h.01" />
                </svg>
                {{ __('Users') }}
                <svg :class="{'rotate-90': open}" class="ml-auto h-5 w-5 transform transition-transform duration-200 text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300" 
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            <div x-show="open" class="mt-1 space-y-1 pl-12">
                <a href="{{ route('users.index') }}" 
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ $isActive('users.index') }} transition-colors duration-200">
                    {{ __('All Users') }}
                </a>
                @can('create', App\Models\User::class)
                <a href="{{ route('users.create') }}" 
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ $isActive('users.create') }} transition-colors duration-200">
                    {{ __('Add New') }}
                </a>
                @endcan
            </div>
        </div>
        @endcan

        <!-- Roles & Permissions -->
        <!-- <div x-data="{ open: {{ in_array($currentRoute, ['roles.index', 'roles.create', 'roles.edit', 'permissions.index']) ? 'true' : 'false' }} }">
            <button @click="open = !open" 
                    class="group w-full flex items-center px-3 py-2 text-sm font-medium rounded-md {{ $isActive(['roles.index', 'roles.create', 'roles.edit', 'permissions.index']) }} transition-colors duration-200">
                <svg class="mr-3 h-6 w-6 flex-shrink-0 {{ in_array($currentRoute, ['roles.index', 'roles.create', 'roles.edit', 'permissions.index']) ? 'text-indigo-500 dark:text-indigo-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }}" 
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                {{ __('Roles & Permissions') }}
                <svg :class="{'rotate-90': open}" class="ml-auto h-5 w-5 transform transition-transform duration-200 text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300" 
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            <div x-show="open" class="mt-1 space-y-1 pl-12">
                <a href="{{ route('roles.index') }}" 
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ $isActive('roles.index') }} transition-colors duration-200">
                    {{ __('Roles') }}
                </a>
                <a href="{{ route('permissions.index') }}" 
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ $isActive('permissions.index') }} transition-colors duration-200">
                    {{ __('Permissions') }}
                </a>
            </div>
        </div> -->

        <!-- Categories -->
        <div x-data="{ open: {{ in_array($currentRoute, ['categories.index', 'categories.create', 'categories.edit']) ? 'true' : 'false' }} }">
            <button @click="open = !open" 
                    class="group w-full flex items-center px-3 py-2 text-sm font-medium rounded-md {{ $isActive(['categories.index', 'categories.create', 'categories.edit']) }} transition-colors duration-200">
                <svg class="mr-3 h-6 w-6 flex-shrink-0 {{ in_array($currentRoute, ['categories.index', 'categories.create', 'categories.edit']) ? 'text-indigo-500 dark:text-indigo-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }}" 
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                </svg>
                {{ __('Categories') }}
                <svg :class="{'rotate-90': open}" class="ml-auto h-5 w-5 transform transition-transform duration-200 text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300" 
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            <div x-show="open" class="mt-1 space-y-1 pl-12">
                <a href="{{ route('categories.index') }}" 
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ $isActive('categories.index') }} transition-colors duration-200">
                    {{ __('All Categories') }}
                </a>
                <a href="{{ route('categories.create') }}" 
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ $isActive('categories.create') }} transition-colors duration-200">
                    {{ __('Add New') }}
                </a>
            </div>
        </div>

        <!-- Products -->
        <div x-data="{ open: {{ in_array($currentRoute, ['products.index', 'products.create', 'products.edit']) ? 'true' : 'false' }} }">
            <button @click="open = !open" 
                    class="group w-full flex items-center px-3 py-2 text-sm font-medium rounded-md {{ $isActive(['products.index', 'products.create', 'products.edit']) }} transition-colors duration-200">
                <svg class="mr-3 h-6 w-6 flex-shrink-0 {{ in_array($currentRoute, ['products.index', 'products.create', 'products.edit']) ? 'text-indigo-500 dark:text-indigo-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }}" 
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                {{ __('Products') }}
                <svg :class="{'rotate-90': open}" class="ml-auto h-5 w-5 transform transition-transform duration-200 text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300" 
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            <div x-show="open" class="mt-1 space-y-1 pl-12">
                <a href="{{ route('products.index') }}" 
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ $isActive('products.index') }} transition-colors duration-200">
                    {{ __('All Products') }}
                </a>
                <a href="{{ route('products.create') }}" 
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ $isActive('products.create') }} transition-colors duration-200">
                    {{ __('Add New') }}
                </a>
            </div>
        </div>

        <!-- Recipes -->
        <div x-data="{ open: {{ in_array($currentRoute, ['recipes.index', 'recipes.create', 'recipes.edit', 'recipes.show']) ? 'true' : 'false' }} }">
            <button @click="open = !open" 
                    class="group w-full flex items-center px-3 py-2 text-sm font-medium rounded-md {{ $isActive(['recipes.index', 'recipes.create', 'recipes.edit', 'recipes.show']) }} transition-colors duration-200">
                <svg class="mr-3 h-6 w-6 flex-shrink-0 {{ in_array($currentRoute, ['recipes.index', 'recipes.create', 'recipes.edit', 'recipes.show']) ? 'text-indigo-500 dark:text-indigo-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }}" 
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                {{ __('Recipes') }}
                <svg :class="{'rotate-90': open}" class="ml-auto h-5 w-5 transform transition-transform duration-200 text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300" 
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            <div x-show="open" class="mt-1 space-y-1 pl-12">
                <a href="{{ route('recipes.index') }}" 
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ $isActive('recipes.index') }} transition-colors duration-200">
                    {{ __('All Recipes') }}
                </a>
                <a href="{{ route('recipes.create') }}" 
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ $isActive('recipes.create') }} transition-colors duration-200">
                    {{ __('Add New') }}
                </a>
            </div>
        </div>

        <!-- Suppliers -->
        <div x-data="{ 
            isOpen: {{ in_array($currentRoute, ['suppliers.index', 'suppliers.create', 'suppliers.edit', 'suppliers.show']) ? 'true' : 'false' }},
            toggle() { this.isOpen = !this.isOpen },
            init() {
                // Auto-open if current route matches
                if({{ in_array($currentRoute, ['suppliers.index', 'suppliers.create', 'suppliers.edit', 'suppliers.show']) ? 'true' : 'false' }}) {
                    this.isOpen = true;
                }
            }
        }">
            <button @click="toggle" 
                    class="group w-full flex items-center px-3 py-2 text-sm font-medium rounded-md {{ $isActive(['suppliers.index', 'suppliers.create', 'suppliers.edit', 'suppliers.show']) }} transition-colors duration-200">
                <svg class="mr-3 h-6 w-6 flex-shrink-0 {{ in_array($currentRoute, ['suppliers.index', 'suppliers.create', 'suppliers.edit', 'suppliers.show']) ? 'text-indigo-500 dark:text-indigo-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }}" 
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                {{ __('Suppliers') }}
                <svg :class="{'rotate-90': isOpen}" class="ml-auto h-5 w-5 transform transition-transform duration-200 text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300" 
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            <div x-show="isOpen" 
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="mt-1 space-y-1 pl-12">
                <a href="{{ route('suppliers.index') }}" 
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ $isActive('suppliers.index') }} transition-colors duration-200">
                    {{ __('All Suppliers') }}
                </a>
                <a href="{{ route('suppliers.create') }}" 
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ $isActive('suppliers.create') }} transition-colors duration-200">
                    {{ __('Add Supplier') }}
                </a>
            </div>
        </div>

        <!-- Purchase Orders -->
        <div x-data="{ open: {{ in_array($currentRoute, ['purchase-orders.index', 'purchase-orders.create', 'purchase-orders.show']) ? 'true' : 'false' }} }">
            <button @click="open = !open" 
                    class="group w-full flex items-center px-3 py-2 text-sm font-medium rounded-md {{ $isActive(['purchase-orders.index', 'purchase-orders.create', 'purchase-orders.show']) }} transition-colors duration-200">
                <svg class="mr-3 h-6 w-6 flex-shrink-0 {{ in_array($currentRoute, ['purchase-orders.index', 'purchase-orders.create', 'purchase-orders.show']) ? 'text-indigo-500 dark:text-indigo-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }}" 
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                {{ __('Purchase Orders') }}
                <svg :class="{'rotate-90': open}" class="ml-auto h-5 w-5 transform transition-transform duration-200 text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300" 
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            <div x-show="open" class="mt-1 space-y-1 pl-12">
                <a href="{{ route('purchase-orders.index') }}" 
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ $isActive('purchase-orders.index') }} transition-colors duration-200">
                    {{ __('All Orders') }}
                </a>
                <a href="{{ route('purchase-orders.create') }}" 
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ $isActive('purchase-orders.create') }} transition-colors duration-200">
                    {{ __('New Order') }}
                </a>
            </div>
        </div>

        <!-- Sales -->
        <div x-data="{ open: {{ in_array($currentRoute, ['sales.index', 'sales.create', 'sales.show']) ? 'true' : 'false' }} }">
            <button @click="open = !open" 
                    class="group w-full flex items-center px-3 py-2 text-sm font-medium rounded-md {{ $isActive(['sales.index', 'sales.create', 'sales.show']) }} transition-colors duration-200">
                <svg class="mr-3 h-6 w-6 flex-shrink-0 {{ in_array($currentRoute, ['sales.index', 'sales.create', 'sales.show']) ? 'text-indigo-500 dark:text-indigo-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }}" 
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                {{ __('Sales') }}
                <svg :class="{'rotate-90': open}" class="ml-auto h-5 w-5 transform transition-transform duration-200 text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300" 
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            <div x-show="open" class="mt-1 space-y-1 pl-12">
                <a href="{{ route('sales.index') }}" 
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ $isActive('sales.index') }} transition-colors duration-200">
                    {{ __('All Sales') }}
                </a>
                <a href="{{ route('sales.create') }}" 
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ $isActive('sales.create') }} transition-colors duration-200">
                    {{ __('New Sale') }}
                </a>
            </div>
        </div>

        <!-- Customers -->
        <div x-data="{ open: {{ in_array($currentRoute, ['customers.index', 'customers.create', 'customers.edit', 'customers.show']) ? 'true' : 'false' }} }">
            <button @click="open = !open" 
                    class="group w-full flex items-center px-3 py-2 text-sm font-medium rounded-md {{ $isActive(['customers.index', 'customers.create', 'customers.edit', 'customers.show']) }} transition-colors duration-200">
                <svg class="mr-3 h-6 w-6 flex-shrink-0 {{ in_array($currentRoute, ['customers.index', 'customers.create', 'customers.edit', 'customers.show']) ? 'text-indigo-500 dark:text-indigo-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }}" 
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                {{ __('Customers') }}
                <svg :class="{'rotate-90': open}" class="ml-auto h-5 w-5 transform transition-transform duration-200 text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300" 
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            <div x-show="open" class="mt-1 space-y-1 pl-12">
                <a href="{{ route('customers.index') }}" 
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ $isActive('customers.index') }} transition-colors duration-200">
                    {{ __('All Customers') }}
                </a>
                <a href="{{ route('customers.create') }}" 
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ $isActive('customers.create') }} transition-colors duration-200">
                    {{ __('Add Customer') }}
                </a>
            </div>
        </div>

        <!-- Reports -->
        <div x-data="{ open: {{ in_array($currentRoute, ['reports.sales', 'reports.inventory']) ? 'true' : 'false' }} }">
            <button @click="open = !open" 
                    class="group w-full flex items-center px-3 py-2 text-sm font-medium rounded-md {{ $isActive(['reports.sales', 'reports.inventory']) }} transition-colors duration-200">
                <svg class="mr-3 h-6 w-6 flex-shrink-0 {{ in_array($currentRoute, ['reports.sales', 'reports.inventory']) ? 'text-indigo-500 dark:text-indigo-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }}" 
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                {{ __('Reports') }}
                <svg :class="{'rotate-90': open}" class="ml-auto h-5 w-5 transform transition-transform duration-200 text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300" 
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            <div x-show="open" class="mt-1 space-y-1 pl-12">
                <a href="{{ route('reports.sales') }}" 
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ $isActive('reports.sales') }} transition-colors duration-200">
                    {{ __('Sales Report') }}
                </a>
                <a href="{{ route('reports.inventory') }}" 
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ $isActive('reports.inventory') }} transition-colors duration-200">
                    {{ __('Inventory Report') }}
                </a>
            </div>
        </div>

        <!-- Market Analysis Group -->
        <div x-data="{ open: {{ in_array($currentRoute, ['market-prediction.index', 'demand-prediction.index', 'competitor-analysis.index', 'competitor-analysis.dashboard']) ? 'true' : 'false' }} }">
            <button @click="open = !open" 
                    class="group w-full flex items-center px-3 py-2 text-sm font-medium rounded-md {{ $isActive(['market-prediction.index', 'demand-prediction.index', 'competitor-analysis.index', 'competitor-analysis.dashboard']) }} transition-colors duration-200">
                <svg class="mr-3 h-6 w-6 flex-shrink-0 {{ in_array($currentRoute, ['market-prediction.index', 'demand-prediction.index', 'competitor-analysis.index', 'competitor-analysis.dashboard']) ? 'text-indigo-500 dark:text-indigo-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }}" 
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                {{ __('Market Analysis') }}
                <svg :class="{'rotate-90': open}" class="ml-auto h-5 w-5 transform transition-transform duration-200 text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300" 
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            <div x-show="open" class="mt-1 space-y-1 pl-12">
                <a href="{{ route('competitor-analysis.dashboard') }}" 
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ $isActive('competitor-analysis.dashboard') }} transition-colors duration-200">
                    {{ __('Competitor Analysis') }}
                </a>
                <a href="{{ route('demand-prediction.index') }}" 
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ $isActive('demand-prediction.index') }} transition-colors duration-200">
                    {{ __('Demand Prediction') }}
                </a>
            </div>
        </div>

        <!-- Alert Settings -->
        <div class="pt-2">
            <a href="{{ route('settings.alerts.index') }}" 
               class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ $isActive('settings.alerts.index') }} transition-colors duration-200">
                <svg class="mr-3 h-6 w-6 flex-shrink-0 {{ $isActive('settings.alerts.index') ? 'text-indigo-500 dark:text-indigo-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }}" 
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                {{ __('Alert Settings') }}
            </a>
        </div>
    </nav>

    <!-- User Profile -->
    <div class="p-4 border-t border-gray-200 dark:border-gray-700">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="h-10 w-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center text-indigo-700 dark:text-indigo-200 font-medium">
                    {{ Auth::check() ? strtoupper(substr(Auth::user()->name, 0, 1)) : 'U' }}
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-200">
                        {{ Auth::check() ? Auth::user()->name : 'Guest User' }}
                    </p>
                    @auth
                    <div class="flex space-x-2">
                        <a href="{{ route('profile.edit') }}" class="text-xs font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300">
                            {{ __('View Profile') }}
                        </a>
                    </div>
                    @endauth
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="ml-2">
                @csrf
                <button type="submit" class="p-1.5 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none" title="{{ __('Sign Out') }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </button>
            </form>
        </div>
    </div>
</div>