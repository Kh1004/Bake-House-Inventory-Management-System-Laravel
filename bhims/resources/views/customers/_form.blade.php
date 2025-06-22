@csrf

<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Name -->
        <div class="col-span-2 md:col-span-1">
            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Full Name <span class="text-red-500">*</span>
            </label>
            <input type="text" name="name" id="name" value="{{ old('name', $customer->name ?? '') }}" required
                   class="mt-1 block w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email -->
        <div class="col-span-2 md:col-span-1">
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Email
            </label>
            <input type="email" name="email" id="email" value="{{ old('email', $customer->email ?? '') }}"
                   class="mt-1 block w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Phone -->
        <div class="col-span-2 md:col-span-1">
            <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Phone <span class="text-red-500">*</span>
            </label>
            <input type="tel" name="phone" id="phone" value="{{ old('phone', $customer->phone ?? '') }}" required
                   class="mt-1 block w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            @error('phone')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- GST Number -->
        <div class="col-span-2 md:col-span-1">
            <label for="gst_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                GST Number
            </label>
            <input type="text" name="gst_number" id="gst_number" value="{{ old('gst_number', $customer->gst_number ?? '') }}"
                   class="mt-1 block w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            @error('gst_number')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Address -->
        <div class="col-span-2">
            <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Address
            </label>
            <textarea name="address" id="address" rows="2"
                      class="mt-1 block w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old('address', $customer->address ?? '') }}</textarea>
            @error('address')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- City -->
        <div class="col-span-2 md:col-span-1">
            <label for="city" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                City
            </label>
            <input type="text" name="city" id="city" value="{{ old('city', $customer->city ?? '') }}"
                   class="mt-1 block w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            @error('city')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- State -->
        <div class="col-span-2 md:col-span-1">
            <label for="state" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                State
            </label>
            <input type="text" name="state" id="state" value="{{ old('state', $customer->state ?? '') }}"
                   class="mt-1 block w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            @error('state')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Postal Code -->
        <div class="col-span-2 md:col-span-1">
            <label for="postal_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Postal Code
            </label>
            <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code', $customer->postal_code ?? '') }}"
                   class="mt-1 block w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            @error('postal_code')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Country -->
        <div class="col-span-2 md:col-span-1">
            <label for="country" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Country
            </label>
            <input type="text" name="country" id="country" value="{{ old('country', $customer->country ?? 'India') }}"
                   class="mt-1 block w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            @error('country')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Notes -->
        <div class="col-span-2">
            <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Notes
            </label>
            <textarea name="notes" id="notes" rows="3"
                      class="mt-1 block w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old('notes', $customer->notes ?? '') }}</textarea>
            @error('notes')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- Form Actions -->
    <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
        <a href="{{ route('customers.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:bg-gray-600">
            Cancel
        </a>
        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            {{ isset($customer) ? 'Update' : 'Create' }} Customer
        </button>
    </div>
</div>
