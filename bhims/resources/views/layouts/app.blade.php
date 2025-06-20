<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50 dark:bg-gray-900">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'BHIMS') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    
    <!-- Custom styles -->
    <style>
        [x-cloak] { display: none !important; }
        body { 
            font-family: 'Inter', sans-serif;
            @apply bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100;
        }
    </style>
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="h-full" x-data="{ sidebarOpen: false }">
    <div class="min-h-screen flex flex-col">
        <!-- Top Navigation -->
        @include('layouts.top-navigation')

        <div class="flex h-full">
            <!-- Mobile sidebar -->
            <div x-show="sidebarOpen" 
                 x-transition:enter="transition ease-in-out duration-300 transform"
                 x-transition:enter-start="-translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transition ease-in-out duration-300 transform"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="-translate-x-full"
                 class="fixed inset-0 z-40 lg:hidden" 
                 x-cloak>
                <div class="fixed inset-0 bg-gray-600 bg-opacity-75" 
                     @click="sidebarOpen = false" 
                     aria-hidden="true">
                </div>
                <div class="relative flex flex-col w-64 h-full bg-white dark:bg-gray-800">
                    <div class="absolute top-0 right-0 -mr-12 pt-2">
                        <button @click="sidebarOpen = false" class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                            <span class="sr-only">Close sidebar</span>
                            <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="flex-1 h-0 pt-5 pb-4 overflow-y-auto">
                        <div class="flex-shrink-0 flex items-center px-4">
                            <x-application-logo class="h-8 w-auto text-indigo-600 dark:text-indigo-400" />
                            <span class="ml-2 text-xl font-bold text-gray-900 dark:text-white">BHIMS</span>
                        </div>
                        <nav class="mt-5 px-2 space-y-1">
                            @include('layouts.navigation')
                        </nav>
                    </div>
                </div>
            </div>

            <!-- Static sidebar for desktop -->
            <div class="hidden lg:flex lg:flex-shrink-0">
                <div class="flex flex-col w-64 border-r border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
                    <div class="flex-1 flex flex-col pt-5 pb-4 overflow-y-auto">
                        <div class="flex items-center flex-shrink-0 px-4">
                            <x-application-logo class="h-8 w-auto text-indigo-600 dark:text-indigo-400" />
                            <span class="ml-2 text-xl font-bold text-gray-900 dark:text-white">BHIMS</span>
                        </div>
                        <nav class="mt-5 flex-1 px-2 space-y-1">
                            @include('layouts.navigation')
                        </nav>
                    </div>
                </div>
            </div>

            <!-- Main content -->
            <div class="flex-1 overflow-auto">
                <div class="flex flex-col h-full">
                    <!-- Mobile menu button -->
                    <div class="lg:hidden flex items-center px-4 py-4">
                        <button @click="sidebarOpen = true" class="text-gray-500 hover:text-gray-600 focus:outline-none">
                            <span class="sr-only">Open sidebar</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                    
                    <main class="flex-1 pb-8 overflow-y-auto">
                        <div class="py-2 lg:py-6">
                            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                                @if (isset($header))
                                    <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $header }}</h1>
                                @endif
                                {{ $slot }}
                            </div>
                        </div>
                    </main>
                </div>
            </div>
        </div>
    </div>

    <!-- Alpine.js & Plugins -->
    <script defer src="https://unpkg.com/@alpinejs/intersect@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Flowbite JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    
    @stack('scripts')
    
    <!-- User Profile Modal -->
    <div x-data="{ open: false }" 
         @click.away="open = false"
         class="relative">
        <button @click="open = !open" 
                class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <span class="sr-only">Open user menu</span>
            <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center">
                <span class="text-indigo-600 font-medium">{{ substr(auth()->user()->name, 0, 1) }}</span>
            </div>
        </button>
        
        <!-- Dropdown menu -->
        <div x-show="open" 
             x-transition:enter="transition ease-out duration-100"
             x-transition:enter-start="transform opacity-0 scale-95"
             x-transition:enter-end="transform opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-75"
             x-transition:leave-start="transform opacity-100 scale-100"
             x-transition:leave-end="transform opacity-0 scale-95"
             class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 py-1 z-50"
             role="menu" 
             aria-orientation="vertical" 
             aria-labelledby="user-menu"
             style="display: none;">
            <a href="{{ route('profile.edit') }}" 
               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
               role="menuitem">
                <i class="fas fa-user-circle mr-2"></i> Your Profile
            </a>
            <a href="#" 
               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
               role="menuitem">
                <i class="fas fa-cog mr-2"></i> Settings
            </a>
            <form method="POST" action="{{ route('logout') }}" class="block w-full text-left">
                @csrf
                <button type="submit" 
                        class="w-full px-4 py-2 text-sm text-left text-gray-700 hover:bg-gray-100"
                        role="menuitem">
                    <i class="fas fa-sign-out-alt mr-2"></i> Sign out
                </button>
            </form>
        </div>
    </div>
</body>
</html>

    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    
    <!-- Flowbite JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    
    @vite(['resources/js/app.js'])
</html>
