<div class="space-y-6">
    <div>
        <h3 class="text-lg font-medium text-gray-900">
            {{ __('Delete Account') }}
        </h3>
        <p class="mt-1 text-sm text-gray-600">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please ensure you have backed up any important data.') }}
        </p>
    </div>

    <div x-data="{ confirmingUserDeletion: false }" class="mt-6">
        <x-danger-button
            x-on:click="confirmingUserDeletion = true"
            x-show="!confirmingUserDeletion"
        >
            <i class="fas fa-trash-alt mr-2"></i> {{ __('Delete Account') }}
        </x-danger-button>

        <div x-show="confirmingUserDeletion" 
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="p-6 bg-white rounded-lg shadow-sm border border-red-100">
            <h3 class="text-lg font-medium text-red-700 flex items-center">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                {{ __('Are you sure you want to delete your account?') }}
            </h3>

            <p class="mt-2 text-sm text-gray-600">
                {{ __('This action cannot be undone. All your data will be permanently removed from our servers. This includes:') }}
            </p>

            <ul class="mt-2 text-sm text-gray-600 list-disc list-inside space-y-1">
                <li>{{ __('Your profile information') }}</li>
                <li>{{ __('Your account settings and preferences') }}</li>
                <li>{{ __('All your personal data stored in the system') }}</li>
            </ul>

            <form method="post" action="{{ route('profile.destroy') }}" class="mt-6 space-y-4">
                @csrf
                @method('delete')

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">
                        {{ __('Enter your password to confirm') }}
                    </label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-key text-gray-400"></i>
                        </div>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               required
                               class="block w-full pl-10 border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm"
                               placeholder="{{ __('Current password') }}">
                    </div>
                    @error('password', 'userDeletion')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" 
                            x-on:click="confirmingUserDeletion = false"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ __('Cancel') }}
                    </button>
                    
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <i class="fas fa-trash-alt mr-2"></i> {{ __('Delete My Account') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
