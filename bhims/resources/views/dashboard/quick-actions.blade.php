@extends('layouts.app')

@section('title', 'Quick Actions')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-8">
            <div class="flex-1 min-w-0">
                <h1 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    Quick Actions
                </h1>
                <p class="mt-1 text-sm text-gray-500">
                    Common tasks and shortcuts for your bakery operations
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Inventory Management -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        <svg class="h-6 w-6 text-indigo-600 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        Inventory Management
                    </h3>
                    <div class="mt-5 space-y-4">
                        <a href="{{ route('ingredients.create') }}" class="group flex items-center p-3 border border-gray-200 rounded-md hover:bg-gray-50 transition-colors duration-150">
                            <div class="flex-shrink-0 h-10 w-10 rounded-md bg-indigo-100 flex items-center justify-center">
                                <svg class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-base font-medium text-gray-900">Add New Ingredient</p>
                                <p class="text-sm text-gray-500">Add a new ingredient to your inventory</p>
                            </div>
                        </a>
                        <a href="{{ route('ingredients.low-stock') }}" class="group flex items-center p-3 border border-gray-200 rounded-md hover:bg-gray-50 transition-colors duration-150">
                            <div class="flex-shrink-0 h-10 w-10 rounded-md bg-yellow-100 flex items-center justify-center">
                                <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-base font-medium text-gray-900">View Low Stock Items</p>
                                <p class="text-sm text-gray-500">Check items that need restocking</p>
                            </div>
                        </a>
                        <a href="{{ route('ingredients.index') }}" class="group flex items-center p-3 border border-gray-200 rounded-md hover:bg-gray-50 transition-colors duration-150">
                            <div class="flex-shrink-0 h-10 w-10 rounded-md bg-blue-100 flex items-center justify-center">
                                <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-base font-medium text-gray-900">View All Ingredients</p>
                                <p class="text-sm text-gray-500">Browse and manage all ingredients</p>
                            </div>
                        </a>
                        <a href="#" class="group flex items-center p-3 border border-gray-200 rounded-md hover:bg-gray-50 transition-colors duration-150">
                            <div class="flex-shrink-0 h-10 w-10 rounded-md bg-purple-100 flex items-center justify-center">
                                <svg class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16v2a2 2 0 01-2 2H5a2 2 0 01-2-2v-7a2 2 0 012-2h2m3-4H9a2 2 0 00-2 2v7a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-1m-1 4l-3 3m0 0l-3-3m3 3V3" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-base font-medium text-gray-900">Import Ingredients</p>
                                <p class="text-sm text-gray-500">Bulk import from CSV/Excel</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Sales & Orders -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        <svg class="h-6 w-6 text-green-600 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Sales & Orders
                    </h3>
                    <div class="mt-5 space-y-4">
                        <a href="{{ route('sales.create') }}" class="group flex items-center p-3 border border-gray-200 rounded-md hover:bg-gray-50 transition-colors duration-150">
                            <div class="flex-shrink-0 h-10 w-10 rounded-md bg-green-100 flex items-center justify-center">
                                <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-base font-medium text-gray-900">Record New Sale</p>
                                <p class="text-sm text-gray-500">Create a new sales transaction</p>
                            </div>
                        </a>
                        <a href="#" class="group flex items-center p-3 border border-gray-200 rounded-md hover:bg-gray-50 transition-colors duration-150">
                            <div class="flex-shrink-0 h-10 w-10 rounded-md bg-purple-100 flex items-center justify-center">
                                <svg class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-base font-medium text-gray-900">View All Orders</p>
                                <p class="text-sm text-gray-500">Browse and manage customer orders</p>
                            </div>
                        </a>
                        <a href="#" class="group flex items-center p-3 border border-gray-200 rounded-md hover:bg-gray-50 transition-colors duration-150">
                            <div class="flex-shrink-0 h-10 w-10 rounded-md bg-pink-100 flex items-center justify-center">
                                <svg class="h-6 w-6 text-pink-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-base font-medium text-gray-900">Manage Products</p>
                                <p class="text-sm text-gray-500">Add or update bakery products</p>
                            </div>
                        </a>
                        <a href="#" class="group flex items-center p-3 border border-gray-200 rounded-md hover:bg-gray-50 transition-colors duration-150">
                            <div class="flex-shrink-0 h-10 w-10 rounded-md bg-yellow-100 flex items-center justify-center">
                                <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-base font-medium text-gray-900">Today's Specials</p>
                                <p class="text-sm text-gray-500">Set daily special offers</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Reports & Analytics -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        <svg class="h-6 w-6 text-red-600 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        Reports & Analytics
                    </h3>
                    <div class="mt-5 space-y-4">
                        <a href="#" class="group flex items-center p-3 border border-gray-200 rounded-md hover:bg-gray-50 transition-colors duration-150">
                            <div class="flex-shrink-0 h-10 w-10 rounded-md bg-red-100 flex items-center justify-center">
                                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-base font-medium text-gray-900">Sales Report</p>
                                <p class="text-sm text-gray-500">View sales performance metrics</p>
                            </div>
                        </a>
                        <a href="#" class="group flex items-center p-3 border border-gray-200 rounded-md hover:bg-gray-50 transition-colors duration-150">
                            <div class="flex-shrink-0 h-10 w-10 rounded-md bg-blue-100 flex items-center justify-center">
                                <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-base font-medium text-gray-900">Inventory Report</p>
                                <p class="text-sm text-gray-500">Analyze stock levels and trends</p>
                            </div>
                        </a>
                        <a href="#" class="group flex items-center p-3 border border-gray-200 rounded-md hover:bg-gray-50 transition-colors duration-150">
                            <div class="flex-shrink-0 h-10 w-10 rounded-md bg-purple-100 flex items-center justify-center">
                                <svg class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-base font-medium text-gray-900">Performance Analytics</p>
                                <p class="text-sm text-gray-500">View business performance metrics</p>
                            </div>
                        </a>
                        <a href="#" class="group flex items-center p-3 border border-gray-200 rounded-md hover:bg-gray-50 transition-colors duration-150">
                            <div class="flex-shrink-0 h-10 w-10 rounded-md bg-green-100 flex items-center justify-center">
                                <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-base font-medium text-gray-900">Export Data</p>
                                <p class="text-sm text-gray-500">Export reports in various formats</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Quick Actions -->
        <div class="mt-8">
            <h3 class="text-lg font-medium text-gray-900 mb-4">More Actions</h3>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <a href="#" class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 hover:bg-gray-50 transition-colors duration-150">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10 rounded-md bg-indigo-100 flex items-center justify-center">
                            <svg class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-900">Manage Staff</p>
                            <p class="text-xs text-gray-500">User accounts & permissions</p>
                        </div>
                    </div>
                </a>
                <a href="#" class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 hover:bg-gray-50 transition-colors duration-150">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10 rounded-md bg-green-100 flex items-center justify-center">
                            <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-900">Billing & Invoices</p>
                            <p class="text-xs text-gray-500">Manage payments and invoices</p>
                        </div>
                    </div>
                </a>
                <a href="#" class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 hover:bg-gray-50 transition-colors duration-150">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10 rounded-md bg-yellow-100 flex items-center justify-center">
                            <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-900">Quality Control</p>
                            <p class="text-xs text-gray-500">Product quality checks</p>
                        </div>
                    </div>
                </a>
                <a href="#" class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 hover:bg-gray-50 transition-colors duration-150">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10 rounded-md bg-purple-100 flex items-center justify-center">
                            <svg class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-900">System Settings</p>
                            <p class="text-xs text-gray-500">Configure application settings</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection