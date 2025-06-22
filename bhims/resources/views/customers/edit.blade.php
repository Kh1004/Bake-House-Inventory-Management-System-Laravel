@extends('layouts.app')

@section('header', 'Edit Customer')

@section('content')
<div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
    <div class="px-4 py-5 sm:px-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
            Edit Customer: {{ $customer->name }}
        </h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
            Update the customer's information.
        </p>
    </div>
    
    <form action="{{ route('customers.update', $customer->id) }}" method="POST" class="px-4 py-5 sm:p-6">
        @csrf
        @method('PUT')
        @include('customers._form', ['customer' => $customer])
    </form>
</div>
@endsection
