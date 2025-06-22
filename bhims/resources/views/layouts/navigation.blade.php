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

        <!-- Suppliers -->
        <div x-data="{ open: {{ in_array($currentRoute, ['suppliers.index', 'suppliers.create', 'suppliers.edit']) ? 'true' : 'false' }} }">
            <button @click="open = !open" 
                    class="group w-full flex items-center px-3 py-2 text-sm font-medium rounded-md {{ $isActive(['suppliers.index', 'suppliers.create', 'suppliers.edit']) }} transition-colors duration-200">
                <svg class="mr-3 h-6 w-6 flex-shrink-0 {{ in_array($currentRoute, ['suppliers.index', 'suppliers.create', 'suppliers.edit']) ? 'text-indigo-500 dark:text-indigo-400' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }}" 
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                {{ __('Suppliers') }}
                <svg :class="{'rotate-90': open}" class="ml-auto h-5 w-5 transform transition-transform duration-200 text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300" 
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            <div x-show="open" class="mt-1 space-y-1 pl-12">
                <a href="{{ route('suppliers.index') }}" 
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ $isActive('suppliers.index') }} transition-colors duration-200">
                    {{ __('All Suppliers') }}
                </a>
                <a href="{{ route('suppliers.create') }}" 
                   class="group flex items-center px-3 py-2 text-sm font-medium rounded-md {{ $isActive('suppliers.create') }} transition-colors duration-200">
                    {{ __('Add New') }}
                </a>
            </div>
        </div>

        <!-- User Section -->
        <div class="pt-4 mt-4 border-t border-gray-200 dark:border-gray-700">
            <a href="{{ route('profile.edit') }}" 
               class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('profile.edit') ? 'bg-gray-100 text-gray-900 dark:bg-gray-700 dark:text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white' }}">
                <svg class="mr-3 h-6 w-6 {{ request()->routeIs('profile.edit') ? 'text-gray-500' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300' }}" 
                     xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                {{ __('Profile') }}
            </a>
            
            <form method="POST" action="{{ route('logout') }}" class="mt-1">
                @csrf
                <button type="submit" 
                        class="group flex items-center w-full px-2 py-2 text-sm font-medium rounded-md text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white">
                    <svg class="mr-3 h-6 w-6 text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300" 
                         xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    {{ __('Sign out') }}
                </button>
            </form>
        </div>
    </nav>
</div>