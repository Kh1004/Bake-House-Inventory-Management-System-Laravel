@extends('layouts.app')

@section('header', 'Add New Customer')

@section('content')
<div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
    <div class="px-4 py-5 sm:px-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
            Customer Information
        </h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
            Add a new customer to the system.
        </p>
    </div>
    
    <form action="{{ route('customers.store') }}" method="POST" class="px-4 py-5 sm:p-6">
        @include('customers._form', ['customer' => new \App\Models\Customer()])
    </form>
</div>
@endsection
