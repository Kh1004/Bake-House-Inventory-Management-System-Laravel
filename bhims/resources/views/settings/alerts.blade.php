@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-2xl font-semibold mb-6">Alert Settings</h2>
                
                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700">
                        <p>{{ session('success') }}</p>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700">
                        <ul class="list-disc pl-5">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Tabs Navigation -->
                <div class="border-b border-gray-200 mb-6">
                    <nav class="-mb-px flex space-x-8">
                        @foreach($alertTypes as $type)
                            <button 
                                type="button"
                                onclick="showTab('{{ $type['type'] }}')"
                                id="tab-{{ $type['type'] }}"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm {{ $loop->first ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                            >
                                {{ $type['name'] }}
                            </button>
                        @endforeach
                    </nav>
                </div>

                @foreach($alertTypes as $type)
                    @php
                        $config = $userConfigs->firstWhere('alert_type', $type['type']);
                    @endphp
                    
                    <div id="panel-{{ $type['type'] }}" class="tab-panel {{ $loop->first ? '' : 'hidden' }}">
                        @if($config)
                            <!-- Form for updating existing alert -->
                            <form action="{{ route('settings.alerts.update', $config->id) }}" method="POST" class="space-y-6">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="alert_type" value="{{ $type['type'] }}">
                        @else
                            <!-- Form for creating new alert -->
                            <form action="{{ route('settings.alerts.store') }}" method="POST" class="space-y-6">
                                @csrf
                                <input type="hidden" name="alert_type" value="{{ $type['type'] }}">
                        @endif

                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-medium">{{ $type['name'] }}</h3>
                                    <p class="text-sm text-gray-500">
                                        {{ $config && $config->is_active ? 'Alerts are currently active' : 'Alerts are currently disabled' }}
                                    </p>
                                </div>
                                <div class="flex items-center">
                                    <span class="mr-3 text-sm font-medium text-gray-700">
                                        {{ $config && $config->is_active ? 'Enabled' : 'Disabled' }}
                                    </span>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="is_active" value="1" class="sr-only peer" 
                                            {{ $config && $config->is_active ? 'checked' : '' }}>
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                    </label>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Channels -->
                                <div class="space-y-4">
                                    <h4 class="text-sm font-medium text-gray-700">Notification Channels</h4>
                                    <div class="space-y-2">
                                        @foreach(['email' => 'Email', 'in_app' => 'In-App', 'sms' => 'SMS'] as $channel => $label)
                                            <div class="flex items-center">
                                                <input id="channel-{{ $type['type'] }}-{{ $channel }}" 
                                                    name="channels[]" 
                                                    type="checkbox" 
                                                    value="{{ $channel }}"
                                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                                    {{ $config && is_array($config->channels) && in_array($channel, $config->channels) ? 'checked' : '' }}>
                                                <label for="channel-{{ $type['type'] }}-{{ $channel }}" class="ml-2 block text-sm text-gray-700">
                                                    {{ $label }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Thresholds -->
                                <div class="space-y-4">
                                    <h4 class="text-sm font-medium text-gray-700">Alert Thresholds</h4>
                                    @if($type['type'] === 'low_stock')
                                        <div class="space-y-2">
                                            <div>
                                                <label for="warning_level" class="block text-sm font-medium text-gray-700">Warning Level (%)</label>
                                                <input type="number" name="thresholds[warning_level]" id="warning_level" 
                                                    value="{{ $config ? ($config->thresholds['warning_level'] ?? '') : $type['default_thresholds']['warning_level'] }}"
                                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                            </div>
                                            <div>
                                                <label for="critical_level" class="block text-sm font-medium text-gray-700">Critical Level (%)</label>
                                                <input type="number" name="thresholds[critical_level]" id="critical_level" 
                                                    value="{{ $config ? ($config->thresholds['critical_level'] ?? '') : $type['default_thresholds']['critical_level'] }}"
                                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                            </div>
                                        </div>
                                    @elseif($type['type'] === 'price_change')
                                        <div class="space-y-2">
                                            <div>
                                                <label for="percentage_change" class="block text-sm font-medium text-gray-700">Percentage Change (%)</label>
                                                <input type="number" name="thresholds[percentage_change]" id="percentage_change" 
                                                    value="{{ $config ? ($config->thresholds['percentage_change'] ?? '') : $type['default_thresholds']['percentage_change'] }}"
                                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                            </div>
                                            <div>
                                                <label for="time_frame" class="block text-sm font-medium text-gray-700">Time Frame (hours)</label>
                                                <input type="number" name="thresholds[time_frame]" id="time_frame" 
                                                    value="{{ $config ? ($config->thresholds['time_frame'] ?? '') : $type['default_thresholds']['time_frame'] }}"
                                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Custom Message -->
                            <div>
                                <label for="custom_message" class="block text-sm font-medium text-gray-700">Custom Message (optional)</label>
                                <input type="text" name="custom_message" id="custom_message" 
                                    value="{{ $config ? $config->custom_message : '' }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    placeholder="Enter a custom message for this alert">
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Save Settings
                                </button>
                            </div>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Show the selected tab
    function showTab(tabName) {
        // Hide all tab panels
        document.querySelectorAll('.tab-panel').forEach(panel => {
            panel.classList.add('hidden');
        });
        
        // Deactivate all tabs
        document.querySelectorAll('[id^="tab-"]').forEach(tab => {
            tab.classList.remove('border-indigo-500', 'text-indigo-600');
            tab.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
        });
        
        // Show the selected panel
        document.getElementById(`panel-${tabName}`).classList.remove('hidden');
        
        // Activate the selected tab
        const selectedTab = document.getElementById(`tab-${tabName}`);
        selectedTab.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
        selectedTab.classList.add('border-indigo-500', 'text-indigo-600');
    }
    
    // Initialize the first tab as active
    document.addEventListener('DOMContentLoaded', function() {
        const firstTab = document.querySelector('[id^="tab-"]');
        if (firstTab) {
            firstTab.click();
        }
    });
</script>
@endpush

@endsection