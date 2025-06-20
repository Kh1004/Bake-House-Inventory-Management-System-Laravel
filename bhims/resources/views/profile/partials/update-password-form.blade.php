<div class="space-y-6">
    <div>
        <h3 class="text-lg font-medium text-gray-900">{{ __('Update Password') }}</h3>
        <p class="mt-1 text-sm text-gray-600">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </div>

    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        @method('put')

        <div class="space-y-4">
            <!-- Current Password -->
            <div>
                <label for="current_password" class="block text-sm font-medium text-gray-700">
                    {{ __('Current Password') }}
                </label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-lock text-gray-400"></i>
                    </div>
                    <input type="password" id="current_password" name="current_password" 
                           class="block w-full pl-10 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                           autocomplete="current-password"
                           required>
                </div>
                @error('current_password', 'updatePassword')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- New Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">
                    {{ __('New Password') }}
                </label>
                <div class="mt-1">
                    <div class="relative rounded-md shadow-sm">
                        <input type="password" id="password" name="password" autocomplete="new-password" 
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                            <i class="fas fa-key text-gray-400"></i>
                        </div>
                    </div>
                    @error('password', 'updatePassword')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">
                        {{ __('Use 8 or more characters with a mix of letters, numbers & symbols.') }}
                    </p>
                </div>
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                    {{ __('Confirm New Password') }}
                </label>
                <div class="mt-1">
                    <div class="relative rounded-md shadow-sm">
                        <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password" 
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                            <i class="fas fa-check-double text-gray-400"></i>
                        </div>
                    </div>
                    @error('password_confirmation', 'updatePassword')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-100">
            @if (session('status') === 'password-updated')
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="text-sm text-green-600">
                    {{ __('Password updated successfully!') }}
                </div>
            @endif
            
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <i class="fas fa-key mr-2"></i> {{ __('Update Password') }}
            </button>
        </div>
    </form>
</div>
