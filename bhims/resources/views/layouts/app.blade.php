<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50 dark:bg-gray-900">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#4f46e5">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

    <title>@yield('title', config('app.name', 'BHIMS'))</title>

    <!-- Preload critical resources -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Flowbite CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    
    <!-- Custom styles -->
    <style>
        [x-cloak] { 
            display: none !important; 
        }
        
        :root {
            --primary-color: #4f46e5;
            --primary-hover: #4338ca;
            --sidebar-width: 16rem;
        }
        
        body { 
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            @apply bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            text-rendering: optimizeLegibility;
        }
        
        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }
        
        /* Better focus states */
        *:focus-visible {
            @apply outline-none ring-2 ring-offset-2 ring-indigo-500 dark:ring-indigo-400 rounded;
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            @apply bg-gray-100 dark:bg-gray-800;
        }
        
        ::-webkit-scrollbar-thumb {
            @apply bg-gray-300 dark:bg-gray-600 rounded-full hover:bg-gray-400 dark:hover:bg-gray-500;
        }
        }
    </style>
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="h-full bg-gray-50 dark:bg-gray-900" x-data="{ 
    sidebarOpen: window.innerWidth >= 1024,
    init() {
        const checkMobile = () => {
            const isMobile = window.innerWidth < 1024;
            if (isMobile) {
                this.sidebarOpen = false;
                document.body.classList.add('overflow-hidden');
            } else {
                this.sidebarOpen = true;
                document.body.classList.remove('overflow-hidden');
            }
        };

        this.$watch('sidebarOpen', value => {
            if (window.innerWidth < 1024) {
                document.body.classList.toggle('overflow-hidden', value);
            }
        });
        
        window.addEventListener('resize', () => checkMobile());
        checkMobile();
    }
}" x-init="init">
    <div class="min-h-screen flex flex-col bg-gray-50 dark:bg-gray-900">
        <!-- Top Navigation -->
        @include('layouts.top-navigation')

        <!-- Sidebar Backdrop -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" 
             class="lg:hidden fixed inset-0 z-20 bg-black bg-opacity-50 transition-opacity duration-300" 
             :class="{'opacity-100': sidebarOpen, 'opacity-0 pointer-events-none': !sidebarOpen}" 
             x-cloak>
        </div>

        <div class="flex flex-1 overflow-hidden">
            <!-- Sidebar -->
            <div x-show="sidebarOpen"
                 x-transition:enter="transition ease-in-out duration-150"
                 x-transition:enter-start="-translate-x-full lg:translate-x-0"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transition ease-in-out duration-150"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="-translate-x-full lg:translate-x-0"
                 @click.away="if (window.innerWidth < 1024) sidebarOpen = false"
                 class="fixed inset-y-0 left-0 z-30 w-64 h-full overflow-y-auto bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 lg:static lg:flex lg:flex-shrink-0 lg:flex-col lg:w-64 transform transition-transform duration-200 ease-in-out"
                 :class="{'-translate-x-full lg:translate-x-0': !sidebarOpen}"
                 x-cloak>
                @include('layouts.navigation')
            </div>

            <!-- Main content area -->
            <div class="flex-1 flex flex-col min-h-0">
                <!-- Page header -->
                <header class="bg-white dark:bg-gray-800 shadow-sm sticky top-0 z-10">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden mr-4 text-gray-500 hover:text-gray-600 focus:outline-none">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                    </svg>
                                </button>
                                <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">
                                    @yield('header', 'Dashboard')
                                </h1>
                            </div>
                            <div class="flex items-center space-x-4">
                                @yield('header-actions')
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Page content -->
                <main class="flex-1 overflow-y-auto focus:outline-none" tabindex="0">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                        @if(session('success'))
                            <div class="mb-6 rounded-md bg-green-50 p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                                </div>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="mb-6 rounded-md bg-red-50 p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                                </div>
                            </div>
                        @endif

                        @if (isset($header))
                            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $header }}</h1>
                        @endif
                        @yield('content', $slot ?? '')
                    </div>
                </main>
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
