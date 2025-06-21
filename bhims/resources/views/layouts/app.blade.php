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
            <!-- Single responsive sidebar -->
            <div x-data="{ isMobile: window.innerWidth < 1024 }"
                 x-init="
                    window.addEventListener('resize', () => isMobile = window.innerWidth < 1024);
                    // Close sidebar by default on mobile
                    if (window.innerWidth < 1024) {
                        $dispatch('sidebar-close');
                    }
                 "
                 x-show="sidebarOpen || !isMobile"
                 x-transition:enter="transition-transform duration-300 ease-in-out"
                 x-transition:enter-start="-translate-x-full lg:translate-x-0"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transition-transform duration-200 ease-in-out"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="-translate-x-full lg:translate-x-0"
                 class="fixed inset-y-0 left-0 z-40 w-64 lg:relative lg:z-30 lg:translate-x-0"
                 :class="{
                    'lg:hidden': !sidebarOpen && !isMobile,
                    'bg-white dark:bg-gray-800': true
                 }"
                 @keydown.escape.window="if(isMobile) sidebarOpen = false">
                <div class="relative flex flex-col h-full">
                    <!-- Close button (always visible when sidebar is open) -->
                    <div x-show="sidebarOpen" class="absolute top-2 right-2 z-50">
                        <button @click="sidebarOpen = false" class="flex items-center justify-center h-10 w-10 rounded-full bg-white dark:bg-gray-700 shadow-md hover:bg-gray-100 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <span class="sr-only">Close sidebar</span>
                            <svg class="h-6 w-6 text-gray-800 dark:text-gray-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Sidebar content -->
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
            <div class="flex-1 overflow-auto transition-all duration-300 ease-in-out" 
                 :class="{'lg:ml-64': sidebarOpen, 'lg:ml-0': !sidebarOpen}">
                <!-- Close sidebar when clicking on content on mobile -->
                <div x-show="sidebarOpen"
                     x-transition:enter="transition-opacity duration-300 ease-in-out"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-75"
                     x-transition:leave="transition-opacity duration-200 ease-in-out"
                     x-transition:leave-start="opacity-75"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 bg-gray-600 bg-opacity-75 z-30 lg:hidden" 
                     @click="sidebarOpen = false" 
                     x-cloak>
                </div>
                <div class="flex flex-col h-full">
                    <!-- Mobile menu button -->
                    <div class="lg:hidden flex items-center px-4 py-4">
                        <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 hover:text-gray-600 focus:outline-none">
                            <span class="sr-only">Toggle sidebar</span>
                            <svg x-show="!sidebarOpen" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            <svg x-show="sidebarOpen" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-cloak>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <main class="flex-1 pb-8 overflow-y-auto">
                        <div class="py-2 lg:py-6">
                            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4">
                                @if (session('success'))
                                    <div class="rounded-md bg-green-50 p-4 mb-4">
                                        <div class="flex">
                                            <div class="flex-shrink-0">
                                                <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if (session('error'))
                                    <div class="rounded-md bg-red-50 p-4 mb-4">
                                        <div class="flex">
                                            <div class="flex-shrink-0">
                                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if (isset($header))
                                    <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $header }}</h1>
                                @endif
                                @yield('content', $slot ?? '')
                            </div>
                        </div>
                    </main>
                </div>
            </div>
        </div>
    </div>

    <!-- Alpine.js & Plugins -->
    <script defer src="https://unpkg.com/@alpinejs/intersect@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://unpkg.com/@alpinejs/transition@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Flowbite JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    
    @stack('scripts')
    
    <script>
        // Initialize sidebar state based on screen size
        document.addEventListener('alpine:init', () => {
            Alpine.data('sidebar', () => ({
                sidebarOpen: window.innerWidth >= 1024,
                
                init() {
                    // Update sidebar state on window resize
                    window.addEventListener('resize', () => {
                        if (window.innerWidth >= 1024) {
                            this.sidebarOpen = true;
                        } else {
                            this.sidebarOpen = false;
                        }
                    });
                    
                    // Close sidebar when clicking outside on mobile
                    window.addEventListener('click', (e) => {
                        const sidebar = this.$el.querySelector('.sidebar-content');
                        const toggleButton = this.$el.querySelector('[x-on\:click*="sidebarOpen"]');
                        
                        if (window.innerWidth < 1024 && 
                            !sidebar.contains(e.target) && 
                            !toggleButton.contains(e.target) &&
                            this.sidebarOpen) {
                            this.sidebarOpen = false;
                        }
                    });
                }
            }));
        });
    </script>
    
    <!-- User Profile Modal -->
    <div x-data="{ showProfileModal: false }" x-cloak>
        <!-- Modal backdrop -->
        <div x-show="showProfileModal" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50 p-4"
             @click.self="showProfileModal = false">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full max-h-[90vh] overflow-y-auto"
                 @click.self.stop>
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Profile Information</h3>
                        <button @click="showProfileModal = false" class="text-gray-400 hover:text-gray-500">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <!-- Profile form content here -->
                    <div class="mt-4">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Update your account's profile information and email address.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
