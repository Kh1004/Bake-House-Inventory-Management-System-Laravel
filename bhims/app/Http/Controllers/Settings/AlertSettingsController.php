<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\AlertConfiguration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AlertSettingsController extends Controller
{
    /**
     * Available alert types with their default configurations
     */
    protected function getAlertTypes()
    {
        return [
            [
                'type' => 'low_stock',
                'name' => 'Low Stock Alerts',
                'description' => 'Get notified when stock levels fall below specified thresholds',
                'default_channels' => ['email', 'in_app'],
                'default_thresholds' => [
                    'warning_level' => 20,
                    'critical_level' => 10,
                ]
            ],
            [
                'type' => 'expiry_alert',
                'name' => 'Expiry Alerts',
                'description' => 'Get notified when products are nearing their expiry date',
                'default_channels' => ['email', 'in_app'],
                'default_thresholds' => [
                    'days_before' => 7,
                ]
            ],
            [
                'type' => 'price_change',
                'name' => 'Price Change Alerts',
                'description' => 'Get notified when product prices change significantly',
                'default_channels' => ['email'],
                'default_thresholds' => [
                    'percentage_change' => 10,
                    'time_frame' => 24,
                ]
            ],
        ];
    }

    /**
     * Display the alert settings page
     */
    public function index()
    {
        $user = Auth::user();
        $alertTypes = $this->getAlertTypes();
        $userConfigs = $user->alertConfigurations()->get();

        return view('settings.alerts', [
            'alertTypes' => $alertTypes,
            'userConfigs' => $userConfigs,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Not used - using the index page for creation
        return redirect()->route('settings.alerts.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return $this->update($request, 'new');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Not used - using the index page for display
        return redirect()->route('settings.alerts.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Not used - using the index page for editing
        return redirect()->route('settings.alerts.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $alertTypes = collect($this->getAlertTypes())->pluck('type')->toArray();
        $alertType = $request->input('alert_type');
        
        // If no alert type in request, try to get it from the existing config
        if (!$alertType && $id !== 'new') {
            $config = AlertConfiguration::findOrFail($id);
            $alertType = $config->alert_type;
        }

        // Validate the request
        $validated = $request->validate([
            'alert_type' => ['required', 'string', Rule::in($alertTypes)],
            'is_active' => ['sometimes', 'boolean'],
            'channels' => ['required', 'array'],
            'channels.*' => ['string', 'in:email,sms,in_app'],
            'thresholds' => ['required', 'array'],
            'custom_message' => ['nullable', 'string', 'max:255'],
        ]);

        // Get the user
        $user = Auth::user();

        // Find or create the alert configuration
        if ($id === 'new') {
            $config = new AlertConfiguration();
            $config->user_id = $user->id;
            $config->alert_type = $validated['alert_type'];
        } else {
            $config = $user->alertConfigurations()->findOrFail($id);
        }

        // Update the configuration
        $config->channels = $validated['channels'];
        $config->thresholds = $validated['thresholds'];
        $config->is_active = $request->boolean('is_active', false);
        $config->custom_message = $validated['custom_message'] ?? null;
        
        $config->save();

        return redirect()
            ->route('settings.alerts.index')
            ->with('success', 'Alert settings updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = Auth::user();
        $config = $user->alertConfigurations()->findOrFail($id);
        $config->delete();

        return redirect()
            ->route('settings.alerts.index')
            ->with('success', 'Alert configuration deleted successfully!');
    }
}
