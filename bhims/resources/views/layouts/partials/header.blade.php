<!-- Top Navigation -->
<nav class="fixed top-0 left-0 right-0 z-40 bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700 lg:left-64">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Mobile menu button -->
                <div class="lg:hidden flex items-center">
                    <button @click="sidebarOpen = !sidebarOpen" class="inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
                        <span class="sr-only">Open main menu</span>
                        <svg x-show="!sidebarOpen" class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg x-show="sidebarOpen" class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center lg:hidden ml-4">
                    <a href="{{ route('dashboard') }}" class="flex items-center">
                        <svg class="h-8 w-auto text-indigo-600 dark:text-indigo-400" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M13 10V3L4 14H11V21L20 10H13Z" fill="currentColor" />
                        </svg>
                        <span class="ml-2 text-xl font-semibold text-gray-900 dark:text-white">BHIMS</span>
                    </a>
                </div>
            </div>

            <div class="flex items-center">
                <!-- Theme Toggle -->
                <button @click="$store.theme.toggle()" class="ml-4 p-2 rounded-full text-gray-500 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <span class="sr-only">Toggle dark mode</span>
                    <svg x-show="!$store.theme.dark" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                    <svg x-show="$store.theme.dark" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </button>

                <!-- Notifications & Alerts -->
                <div class="ml-4 relative" x-data="{ open: false }">
                    <button @click="open = !open" class="p-1 rounded-full text-gray-500 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 relative">
                        <span class="sr-only">View notifications and alerts</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        @if($activeAlertsCount > 0)
                            <span class="notification-badge absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-500 rounded-full">
                                {{ $activeAlertsCount }}
                            </span>
                        @else
                            <span class="notification-badge hidden"></span>
                        @endif
                    </button>
                    <div x-show="open" @click.away="open = false" class="origin-top-right absolute right-0 mt-2 w-80 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 focus:outline-none z-50" style="display: none;">
                        <div class="py-1">
                            <div class="px-4 py-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider flex justify-between items-center">
                                <span>Alerts ({{ $activeAlertsCount }})</span>
                                @if($activeAlertsCount > 0)
                                    <button @click="open = false" class="text-gray-400 hover:text-gray-500">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                @endif
                            </div>
                            
                            @if($activeAlertsCount > 0)
                                <div class="max-h-96 overflow-y-auto">
                                    @foreach($alerts as $alert)
                                        <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <div class="flex items-start">
                                                <div class="flex-shrink-0 pt-0.5">
                                                    <svg class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                    </svg>
                                                </div>
                                                <div class="ml-3 flex-1">
                                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                        {{ $alert->title }}
                                                    </p>
                                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                                        {{ $alert->message }}
                                                    </p>
                                                    <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">
                                                        {{ $alert->created_at->diffForHumans() }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="border-t border-gray-200 dark:border-gray-700">
                                    <a href="{{ route('alerts.index') }}" class="block px-4 py-2 text-sm text-center text-indigo-600 dark:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-gray-700">
                                        View All Alerts
                                    </a>
                                </div>
                            @else
                                <div class="px-4 py-3 text-sm text-center text-gray-500 dark:text-gray-400">
                                    No active alerts
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Profile dropdown -->
                <div class="ml-4 relative" x-data="{ open: false }">
                    <button @click="open = !open" class="max-w-xs bg-white dark:bg-gray-800 flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" id="user-menu" aria-expanded="false" aria-haspopup="true">
                        <span class="sr-only">Open user menu</span>
                        <div class="h-8 w-8 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center text-indigo-700 dark:text-indigo-200 font-medium">
                            {{ Auth::check() ? strtoupper(substr(Auth::user()->name, 0, 1)) : 'U' }}
                        </div>
                    </button>
                    <div x-show="open" @click.away="open = false" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 focus:outline-none z-50" style="display: none;" role="menu" aria-orientation="vertical" aria-labelledby="user-menu">
                        <div class="py-1" role="none">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">Your Profile</a>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                                    {{ __('Sign out') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- Add padding to the top of main content to account for fixed header -->
<div class="h-16"></div>

@push('scripts')
<script>
    // Function to update the notification count
    function updateNotificationCount() {
        fetch('{{ route('alerts.count') }}', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            const badge = document.querySelector('.notification-badge');
            const counter = document.querySelector('.notification-counter');
            
            if (data.count > 0) {
                if (badge) {
                    badge.textContent = data.count;
                    badge.classList.remove('hidden');
                }
                if (counter) {
                    counter.textContent = `(${data.count})`;
                }
            } else {
                if (badge) badge.classList.add('hidden');
                if (counter) counter.textContent = '';
            }
        })
        .catch(error => console.error('Error updating notification count:', error));
    }

    // Update count every 30 seconds
    document.addEventListener('DOMContentLoaded', function() {
        // Initial update
        updateNotificationCount();
        
        // Set up periodic updates
        setInterval(updateNotificationCount, 30000);
        
        // Also update when the page regains focus
        document.addEventListener('visibilitychange', function() {
            if (!document.hidden) {
                updateNotificationCount();
            }
        });
    });
</script>
@endpush
