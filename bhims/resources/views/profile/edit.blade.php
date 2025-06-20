<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-800 leading-tight">
                <i class="fas fa-user-circle mr-2"></i>{{ __('My Profile') }}
            </h2>
            <div class="flex space-x-3">
                <a href="{{ url()->previous() }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-medium text-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-arrow-left mr-2"></i> Back
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            {{-- Debug Information --}}
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">Debug Information</h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>User ID: {{ $user->id ?? 'Not set' }}</p>
                            <p>Name: {{ $user->name ?? 'Not set' }}</p>
                            <p>Email: {{ $user->email ?? 'Not set' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Success/Error Messages -->
            @if (session('status') === 'profile-information-updated')
                <div class="rounded-md bg-green-50 p-4 mb-6" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">
                                {{ __('Profile information updated successfully.') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            @if (session('status') === 'password-updated')
                <div class="rounded-md bg-green-50 p-4 mb-6" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">
                                {{ __('Password updated successfully.') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Profile Header -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row items-start md:items-center space-y-4 md:space-y-0 md:space-x-6">
                        <div class="relative group">
                            <div class="h-24 w-24 rounded-full bg-indigo-100 flex items-center justify-center text-3xl font-semibold text-indigo-600 relative">
                                <span>{{ substr(auth()->user()->name, 0, 1) }}</span>
                                <div class="absolute inset-0 bg-black bg-opacity-30 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                    <span class="text-white text-sm font-medium">Change</span>
                                </div>
                            </div>
                            <input type="file" class="hidden" id="profile-photo">
                        </div>
                        <div class="flex-1">
                            <h1 class="text-2xl font-bold text-gray-900">{{ auth()->user()->name }}</h1>
                            <p class="text-gray-600 flex items-center">
                                <i class="fas fa-envelope mr-2 text-gray-400"></i>{{ auth()->user()->email }}
                            </p>
                            <p class="text-sm text-gray-500 mt-1 flex items-center">
                                <i class="far fa-calendar-alt mr-2"></i>
                                Member since {{ auth()->user()->created_at->format('F Y') }}
                            </p>
                            @if(auth()->user()->email_verified_at)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mt-2">
                                    <i class="fas fa-check-circle mr-1"></i> Verified
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 mt-2">
                                    <i class="fas fa-exclamation-circle mr-1"></i> Unverified
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs Navigation -->
            <div x-data="tabs()" class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8 overflow-x-auto">
                    <button @click="setActiveTab('profile')"
                        :class="{
                            'border-indigo-500 text-indigo-600': activeTab === 'profile',
                            'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'profile',
                            'flex items-center py-4 px-1 border-b-2 font-medium text-sm whitespace-nowrap': true
                        }"
                        :aria-current="activeTab === 'profile' ? 'page' : undefined">
                        <i class="fas fa-user-circle mr-2"></i> Profile Information
                    </button>
                    <button @click="setActiveTab('security')"
                        :class="{
                            'border-indigo-500 text-indigo-600': activeTab === 'security',
                            'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'security',
                            'flex items-center py-4 px-1 border-b-2 font-medium text-sm whitespace-nowrap': true
                        }"
                        :aria-current="activeTab === 'security' ? 'page' : undefined">
                        <i class="fas fa-shield-alt mr-2"></i> Security & Password
                    </button>
                    <button @click="setActiveTab('danger')"
                        :class="{
                            'border-red-500 text-red-600': activeTab === 'danger',
                            'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'danger',
                            'flex items-center py-4 px-1 border-b-2 font-medium text-sm whitespace-nowrap': true
                        }"
                        :aria-current="activeTab === 'danger' ? 'page' : undefined">
                        <i class="fas fa-exclamation-triangle mr-2"></i> Danger Zone
                    </button>
                </nav>
            </div>

            <!-- Tab Panels with Smooth Transitions -->
            <div x-show="activeTab === 'profile'"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 transform translate-y-2"
                 x-transition:enter-end="opacity-100 transform translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 transform translate-y-0"
                 x-transition:leave-end="opacity-0 transform -translate-y-2"
                 class="space-y-6"
                 style="display: none"
                 x-init="if (!window.location.hash || window.location.hash === '#profile') { $el.style.display = 'block'; }">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 border-b pb-3 mb-6 flex items-center">
                            <i class="fas fa-user-edit mr-2 text-indigo-600"></i> {{ __('Profile Information') }}
                        </h3>
                        @include('profile.partials.update-profile-information-form', ['user' => $user])
                    </div>
                </div>
            </div>

            <div x-show="activeTab === 'security'"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 transform translate-y-2"
                 x-transition:enter-end="opacity-100 transform translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 transform translate-y-0"
                 x-transition:leave-end="opacity-0 transform -translate-y-2"
                 class="space-y-6"
                 style="display: none"
                 x-init="if (window.location.hash === '#security') { $el.style.display = 'block'; }">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 border-b pb-3 mb-6 flex items-center">
                            <i class="fas fa-key mr-2 text-indigo-600"></i> {{ __('Update Password') }}
                        </h3>
                        @include('profile.partials.update-password-form', ['user' => $user])
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 border-b pb-3 mb-6 flex items-center">
                            <i class="fas fa-shield-alt mr-2 text-indigo-600"></i> {{ __('Two-Factor Authentication') }}
                        </h3>
                        <div class="space-y-4">
                            <p class="text-sm text-gray-600">
                                {{ __('Add additional security to your account using two-factor authentication.') }}
                            </p>
                            <button type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <i class="fas fa-mobile-alt mr-2"></i> Set up two-factor authentication
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div x-show="activeTab === 'danger'"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 transform translate-y-2"
                 x-transition:enter-end="opacity-100 transform translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 transform translate-y-0"
                 x-transition:leave-end="opacity-0 transform -translate-y-2"
                 class="space-y-6"
                 style="display: none"
                 x-init="if (window.location.hash === '#danger') { $el.style.display = 'block'; }">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-red-100">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-red-600 border-b border-red-100 pb-3 mb-6 flex items-center">
                            <i class="fas fa-exclamation-triangle mr-2"></i> {{ __('Danger Zone') }}
                        </h3>
                        
                        <div class="space-y-6">
                            <div class="border-b border-gray-200 pb-6">
                                <h4 class="text-md font-medium text-gray-900 mb-2">{{ __('Delete Account') }}</h4>
                                <p class="text-sm text-gray-600 mb-4">
                                    {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
                                </p>
                                @include('profile.partials.delete-user-form', ['user' => $user])
                            </div>
                            
                            <div>
                                <h4 class="text-md font-medium text-gray-900 mb-2">{{ __('Export Data') }}</h4>
                                <p class="text-sm text-gray-600 mb-4">
                                    {{ __('Download all of your personal data in a ZIP file.') }}
                                </p>
                                <button type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <i class="fas fa-file-export mr-2"></i> Export Data
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Tabs component for profile page
        function tabs() {
            return {
                activeTab: window.location.hash ? window.location.hash.substring(1) : 'profile',
                
                init() {
                    // Set initial active tab from URL or default to 'profile'
                    const hash = window.location.hash.substring(1);
                    this.activeTab = ['profile', 'security', 'danger'].includes(hash) ? hash : 'profile';
                    
                    // Handle browser back/forward buttons
                    window.addEventListener('popstate', () => {
                        const newHash = window.location.hash.substring(1);
                        this.activeTab = ['profile', 'security', 'danger'].includes(newHash) ? newHash : 'profile';
                        this.showActiveTab();
                    });
                    
                    // Show the active tab immediately
                    this.$nextTick(() => {
                        this.showActiveTab();
                    });
                },
                
                setActiveTab(tab) {
                    this.activeTab = tab;
                    window.history.pushState(null, null, `#${tab}`);
                    this.showActiveTab();
                },
                
                showActiveTab() {
                    // Small delay to ensure DOM is updated
                    setTimeout(() => {
                        // Hide all tab panels first
                        document.querySelectorAll('[x-show^="activeTab ==="]').forEach(panel => {
                            panel.style.display = 'none';
                        });
                        
                        // Show the active tab panel
                        const activePanel = document.querySelector(`[x-show="activeTab === '${this.activeTab}'"]`);
                        if (activePanel) {
                            activePanel.style.display = 'block';
                        }
                    }, 10);
                }
            };
        }
        
        // Initialize Alpine.js components
        document.addEventListener('alpine:init', () => {
            // Add any Alpine.js component registrations here
        });
    </script>
    @endpush

    @push('styles')
    <style>
        [x-cloak] { display: none !important; }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
        .profile-section {
            transition: all 0.3s ease;
        }
        .profile-section:hover {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
    </style>
    @endpush
</x-app-layout>
