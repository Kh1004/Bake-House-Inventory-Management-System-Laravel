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
        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('dashboard') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="mr-3 h-6 w-6 {{ request()->routeIs('dashboard') ? 'text-gray-500' : 'text-gray-400 group-hover:text-gray-500' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            Dashboard
        </a>

        <!-- User Section -->
        <div class="pt-4 mt-4 border-t border-gray-200">
            <a href="{{ route('profile.edit') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('profile.edit') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <svg class="mr-3 h-6 w-6 {{ request()->routeIs('profile.edit') ? 'text-gray-500' : 'text-gray-400 group-hover:text-gray-500' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Profile
            </a>
            
            <form method="POST" action="{{ route('logout') }}" class="mt-1">
                @csrf
                <button type="submit" class="group flex items-center w-full px-2 py-2 text-sm font-medium rounded-md text-gray-600 hover:bg-gray-50 hover:text-gray-900">
                    <svg class="mr-3 h-6 w-6 text-gray-400 group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Sign out
                </button>
            </form>
        </div>
    </nav>
</div>
