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
        <a href="{{ route('dashboard') }}" 
           class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ $isActive('dashboard') }} transition-colors duration-200">
            <svg class="mr-3 h-6 w-6 flex-shrink-0 {{ $currentRoute === 'dashboard' ? 'text-indigo-500 dark:text-indigo-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }}" 
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            {{ __('Dashboard') }}
        </a>

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
                    {{ __('Sales Reports') }}
                </a>
                <a href="{{ route('reports.inventory') }}" 
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ $isActive('reports.inventory') }} transition-colors duration-200">
                    {{ __('Inventory Reports') }}
                </a>
            </div>
        </div>
    </nav>

    <!-- User Profile -->
    <div class="p-4 border-t border-gray-200 dark:border-gray-700">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="h-10 w-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center text-indigo-700 dark:text-indigo-200 font-medium">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-200">
                        {{ Auth::user()->name }}
                    </p>
                    <div class="flex space-x-2">
                        <a href="{{ route('profile.edit') }}" class="text-xs font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300">
                            {{ __('View Profile') }}
                        </a>
                    </div>
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