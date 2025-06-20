<div class="relative ml-3">
    <x-dropdown align="right" width="48">
        <x-slot name="trigger">
            <button type="button" class="flex items-center max-w-xs text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                <span class="sr-only">Open user menu</span>
                <div class="h-8 w-8 rounded-full bg-indigo-600 flex items-center justify-center text-white font-semibold">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <span class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300 hidden md:inline-block">
                    {{ Auth::user()->name }}
                </span>
                <svg class="ml-1 h-4 w-4 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
        </x-slot>

        <x-slot name="content">
            <div class="py-1">
                <div class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300">
                    <div class="font-medium">{{ Auth::user()->name }}</div>
                    <div class="truncate text-xs text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</div>
                </div>
                
                <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>
                
                <x-dropdown-link :href="route('profile.edit')">
                    <svg class="mr-2 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                    </svg>
                    {{ __('Profile') }}
                </x-dropdown-link>

                <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-dropdown-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        <svg class="mr-2 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd" />
                        </svg>
                        {{ __('Sign out') }}
                    </x-dropdown-link>
                </form>
            </div>
        </x-slot>
    </x-dropdown>
</div>