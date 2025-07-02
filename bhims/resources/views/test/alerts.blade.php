@extends('layouts.app')

@push('scripts')
<script>
    function deleteAlert(button) {
        if (!confirm('Are you sure you want to delete this alert?')) {
            return false;
        }

        const form = button.closest('form');
        
        // Show loading state
        button.disabled = true;
        button.querySelector('.delete-text').textContent = 'Deleting...';

        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                _method: 'DELETE'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Refresh the page to update all counters
                window.location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting the alert.');
            button.disabled = false;
            button.querySelector('.delete-text').textContent = 'Delete';
        });
        
        return false;
    }
</script>
@endpush

@section('content')
<div class="py-4">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white dark:bg-gray-800">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">Alerts & Notifications</h2>
                    <a href="{{ route('settings.alerts.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:ring focus:ring-indigo-200 active:bg-indigo-600 disabled:opacity-25 transition">
                        Alert Settings
                    </a>
                </div>
                
                @if(session('status'))
                    <div class="mb-4 p-4 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 rounded">
                        {{ session('status') }}
                    </div>
                @endif

                @if($alerts->count() > 0)
                    <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-md">
                        <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($alerts as $alert)
                                <li class="{{ $alert->is_read ? 'bg-gray-50 dark:bg-gray-700' : 'bg-white dark:bg-gray-800' }}">
                                    <a href="#" class="block hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <div class="px-4 py-4 sm:px-6">
                                            <div class="flex items-center justify-between">
                                                <p class="text-sm font-medium {{ $alert->is_read ? 'text-gray-500 dark:text-gray-400' : 'text-indigo-600 dark:text-indigo-400' }} truncate">
                                                    {{ $alert->title }}
                                                </p>
                                                <div class="ml-2 flex-shrink-0 flex">
                                                    @if(!$alert->is_read)
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                            New
                                                        </span>
                                                    @endif
                                                    <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                        {{ $alert->priority === 'high' ? 'bg-red-100 text-red-800' : '' }}
                                                        {{ $alert->priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                        {{ $alert->priority === 'low' ? 'bg-blue-100 text-blue-800' : '' }}">
                                                        {{ ucfirst($alert->priority) }} priority
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="mt-2 sm:flex sm:justify-between">
                                                <div class="sm:flex">
                                                    <p class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                                                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                                        </svg>
                                                        {{ $alert->created_at->diffForHumans() }}
                                                    </p>
                                                </div>
                                                <div class="mt-2 flex items-center text-sm text-gray-500 dark:text-gray-400 sm:mt-0">
                                                    <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                                    </svg>
                                                    {{ $alert->user->name ?? 'System' }}
                                                </div>
                                            </div>
                                            <div class="mt-2">
                                                <p class="text-sm text-gray-700 dark:text-gray-300 mb-2">
                                                    {{ $alert->message }}
                                                </p>
                                                <form action="{{ route('alerts.destroy', $alert) }}" method="POST" class="inline" onsubmit="return deleteAlert(this);">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 text-xs">
                                                        <svg class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                        <span class="delete-text">Delete</span>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    
                    <div class="mt-4">
                        {{ $alerts->links() }}
                    </div>
    @else
        <div class="alert alert-warning">
            No alert configurations found in the database.
        </div>
    @endif
    
    <div class="mt-4">
        <a href="{{ route('settings.alerts.index') }}" class="btn btn-primary">
            Go to Alert Settings
        </a>
    </div>
</div>
@endsection
