<nav x-data="{ open: false }" class="bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Logo (for mobile) -->
            <div class="flex-shrink-0 md:hidden">
                <a href="{{ route('dashboard') }}" class="flex items-center">
                    <x-application-logo class="block h-8 w-auto text-indigo-600 dark:text-indigo-400" />
                </a>
            </div>

            <!-- Mobile menu button -->
            <div class="flex items-center md:hidden">
                <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:hover:bg-gray-700">
                    <span class="sr-only">Open main menu</span>
                    <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

            <!-- Desktop navigation -->
            <div class="hidden md:block">
                <div class="ml-10 flex items-baseline space-x-4">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <!-- Add more navigation links here -->
                </div>
            </div>

            <!-- Right side controls -->
            <div class="hidden md:block">
                <div class="ml-4 flex items-center md:ml-6">
                    <!-- User dropdown -->
                    <x-dropdowns.user />
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div x-show="open" @click.away="open = false" class="md:hidden">
        <div class="pt-2 pb-3 space-y-1 sm:px-3">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <!-- Add more mobile navigation links here -->
        </div>
        
        <!-- Mobile user menu -->
        <div class="pt-4 pb-3 border-t border-gray-200 dark:border-gray-700">
            <div class="flex items-center px-5">
                <div class="h-10 w-10 rounded-full bg-indigo-600 flex items-center justify-center text-white font-semibold">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="ml-3">
                    <div class="text-base font-medium text-gray-800 dark:text-white">{{ Auth::user()->name }}</div>
                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</div>
                </div>
            </div>
            <div class="mt-3 px-2 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Your Profile') }}
                </x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Sign out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>