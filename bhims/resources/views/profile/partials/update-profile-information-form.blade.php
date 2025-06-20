@php
    // If $user is not passed, try to get the authenticated user
    $user = $user ?? auth()->user();
@endphp

<div class="space-y-6">
    <p class="text-sm text-gray-600 mb-6">
        {{ __("Update your account's profile information and email address.") }}
    </p>

    @if(!$user)
        <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Error: User data not available</h3>
                </div>
            </div>
        </div>
    @endif

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf
        @method('patch')

        <div class="grid grid-cols-1 gap-y-6 sm:grid-cols-6 sm:gap-x-6">
            <div class="sm:col-span-3">
                <label for="name" class="block text-sm font-medium text-gray-700">{{ __('Full Name') }}</label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-user text-gray-400"></i>
                    </div>
                    <input type="text" name="name" id="name" 
                           value="{{ old('name', $user->name) }}"
                           class="block w-full pl-10 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                           autocomplete="name"
                           required>
                </div>
                @error('name')
                    <p class="mt-2 text-sm text-red-600" id="name-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-4">
                <label for="email" class="block text-sm font-medium text-gray-700">{{ __('Email Address') }}</label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-envelope text-gray-400"></i>
                    </div>
                    <input type="email" name="email" id="email" 
                           value="{{ old('email', $user->email) }}"
                           class="block w-full pl-10 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                           autocomplete="email"
                           required>
                </div>
                @error('email')
                    <p class="mt-2 text-sm text-red-600" id="email-error">{{ $message }}</p>
                @enderror

                @if ($user->hasVerifiedEmail())
                    <div class="mt-2 flex items-center text-sm text-green-600">
                        <i class="fas fa-check-circle mr-1.5"></i>
                        <span>{{ __('Your email address is verified.') }}</span>
                    </div>
                @else
                    <div class="mt-2">
                        <p class="text-sm text-yellow-600">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            {{ __('Your email address is unverified.') }}
                        </p>
                        <button type="button" 
                                form="send-verification"
                                class="mt-1 text-sm font-medium text-indigo-600 hover:text-indigo-500 focus:outline-none focus:underline transition duration-150 ease-in-out">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 text-sm font-medium text-green-600">
                                <i class="fas fa-check-circle mr-1"></i>
                                {{ __('A new verification link has been sent to your email address.') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <div class="pt-5">
            <div class="flex justify-end">
                <button type="button" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    {{ __('Cancel') }}
                </button>
                <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-save mr-2"></i>{{ __('Save Changes') }}
                </button>
            </div>
        </div>
    </form>
</div>
