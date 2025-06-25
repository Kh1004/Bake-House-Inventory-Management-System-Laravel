@extends('layouts.app')

@section('title', 'Recent Activities')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                <h1 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    Recent Activities
                </h1>
                <p class="mt-1 text-sm text-gray-500">
                    Track all system activities and user actions
                </p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input type="text" placeholder="Search activities..." class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
            </div>
        </div>

        <!-- Activity Filters -->
        <div class="mb-6 bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0 sm:space-x-4">
                    <div class="flex-1">
                        <label for="activity-type" class="block text-sm font-medium text-gray-700">Activity Type</label>
                        <select id="activity-type" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option>All Activities</option>
                            <option>Inventory</option>
                            <option>Sales</option>
                            <option>Users</option>
                            <option>System</option>
                        </select>
                    </div>
                    <div class="flex-1">
                        <label for="date-range" class="block text-sm font-medium text-gray-700">Date Range</label>
                        <select id="date-range" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option>Last 7 days</option>
                            <option>Today</option>
                            <option>Yesterday</option>
                            <option>This Month</option>
                            <option>Last Month</option>
                            <option>Custom Range</option>
                        </select>
                    </div>
                    <div class="flex-1">
                        <label for="user-filter" class="block text-sm font-medium text-gray-700">User</label>
                        <select id="user-filter" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option>All Users</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Apply Filters
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activity List -->
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <ul role="list" class="divide-y divide-gray-200">
                @forelse($activities as $activity)
                    <li class="hover:bg-gray-50">
                        <a href="#" class="block">
                            <div class="px-4 py-4 sm:px-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                            @if($activity->causer && $activity->causer->profile_photo_path)
                                                <img class="h-10 w-10 rounded-full" src="{{ asset('storage/' . $activity->causer->profile_photo_path) }}" alt="">
                                            @elseif($activity->causer)
                                                <span class="text-indigo-600 font-medium">{{ substr($activity->causer->name, 0, 1) }}</span>
                                            @else
                                                <svg class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                                </svg>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <p class="text-sm font-medium text-indigo-600 truncate">
                                                {{ $activity->causer ? $activity->causer->name : 'System' }}
                                                <span class="text-gray-500 font-normal">{{ $activity->description }}</span>
                                            </p>
                                            <div class="flex flex-col sm:flex-row sm:items-center text-sm text-gray-500 mt-1">
                                                <span>{{ $activity->created_at->diffForHumans() }}</span>
                                                <span class="hidden sm:inline-block sm:mx-1">•</span>
                                                <span>{{ $activity->created_at->format('M j, Y \a\t g:i A') }}</span>
                                                @if($activity->properties->has('attributes') || $activity->properties->has('old'))
                                                    <span class="hidden sm:inline-block sm:mx-1">•</span>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        {{ ucfirst($activity->log_name) }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="ml-2 flex-shrink-0 flex">
                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                                @if($activity->properties->has('attributes') || $activity->properties->has('old'))
                                    <div class="mt-4 bg-gray-50 p-4 rounded-md">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            @if($activity->properties->has('old') && count($activity->properties->get('old')) > 0)
                                                <div>
                                                    <h4 class="text-xs font-medium text-gray-500 uppercase tracking-wider">Before</h4>
                                                    <div class="mt-1 text-sm text-gray-900 bg-white p-3 rounded border border-gray-200">
                                                        @foreach($activity->properties->get('old') as $key => $value)
                                                            <div class="py-1">
                                                                <span class="font-medium">{{ $key }}:</span>
                                                                <span class="ml-2 text-gray-600">
                                                                    @if(is_array($value) || is_object($value))
                                                                        {{ json_encode($value) }}
                                                                    @else
                                                                        {{ $value ?? 'N/A' }}
                                                                    @endif
                                                                </span>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                            @if($activity->properties->has('attributes'))
                                                <div>
                                                    <h4 class="text-xs font-medium text-gray-500 uppercase tracking-wider">After</h4>
                                                    <div class="mt-1 text-sm text-gray-900 bg-white p-3 rounded border border-gray-200">
                                                        @foreach($activity->properties->get('attributes') as $key => $value)
                                                            <div class="py-1">
                                                                <span class="font-medium">{{ $key }}:</span>
                                                                <span class="ml-2 text-gray-600">
                                                                    @if(is_array($value) || is_object($value))
                                                                        {{ json_encode($value) }}
                                                                    @else
                                                                        {{ $value ?? 'N/A' }}
                                                                    @endif
                                                                </span>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </a>
                    </li>
                @empty
                    <li class="px-4 py-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No activities found</h3>
                        <p class="mt-1 text-sm text-gray-500">Get started by performing some actions in the system.</p>
                    </li>
                @endforelse
            </ul>

            @if($activities->hasPages())
                <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                    <div class="flex-1 flex justify-between sm:hidden">
                        @if($activities->onFirstPage())
                            <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-300 bg-white">
                                Previous
                            </span>
                        @else
                            <a href="{{ $activities->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Previous
                            </a>
                        @endif
                        
                        @if($activities->hasMorePages())
                            <a href="{{ $activities->nextPageUrl() }}" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Next
                            </a>
                        @else
                            <span class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-300 bg-white">
                                Next
                            </span>
                        @endif
                    </div>
                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-700">
                                Showing
                                <span class="font-medium">{{ $activities->firstItem() }}</span>
                                to
                                <span class="font-medium">{{ $activities->lastItem() }}</span>
                                of
                                <span class="font-medium">{{ $activities->total() }}</span>
                                results
                            </p>
                        </div>
                        <div>
                            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                @if($activities->onFirstPage())
                                    <span class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-300">
                                        <span class="sr-only">Previous</span>
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                @else
                                    <a href="{{ $activities->previousPageUrl() }}" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                        <span class="sr-only">Previous</span>
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                @endif

                                @foreach($activities->getUrlRange(1, $activities->lastPage()) as $page => $url)
                                    @if($page == $activities->currentPage())
                                        <span class="relative inline-flex items-center px-4 py-2 border border-indigo-500 bg-indigo-50 text-sm font-medium text-indigo-600">
                                            {{ $page }}
                                        </span>
                                    @else
                                        <a href="{{ $url }}" class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                            {{ $page }}
                                        </a>
                                    @endif
                                @endforeach

                                @if($activities->hasMorePages())
                                    <a href="{{ $activities->nextPageUrl() }}" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                        <span class="sr-only">Next</span>
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                @else
                                    <span class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-300">
                                        <span class="sr-only">Next</span>
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                @endif
                            </nav>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection