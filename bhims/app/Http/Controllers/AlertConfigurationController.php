<?php

namespace App\Http\Controllers;

use App\Models\AlertConfiguration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AlertConfigurationController extends Controller
{
    /**
     * Get all alert configurations for the authenticated user.
     */
    public function index()
    {
        $configs = Auth::user()->alertConfigurations()->get();
        
        return response()->json([
            'success' => true,
            'data' => $configs->map(function($config) {
                return $this->formatAlertConfig($config);
            })
        ]);
    }

    /**
     * Store a new alert configuration.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'alert_type' => 'required|string|in:' . implode(',', [
                AlertConfiguration::TYPE_LOW_STOCK,
                AlertConfiguration::TYPE_PRICE_CHANGE,
                AlertConfiguration::TYPE_DEMAND_SPIKE,
                AlertConfiguration::TYPE_ORDER_THRESHOLD,
            ]),
            'channels' => 'required|array',
            'channels.*' => 'string|in:email,sms,in_app',
            'thresholds' => 'nullable|array',
            'is_active' => 'boolean',
            'custom_message' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Set default values if not provided
        $data = $validator->validated();
        $data['user_id'] = Auth::id();
        
        if (!isset($data['thresholds'])) {
            $data['thresholds'] = AlertConfiguration::getDefaultThresholds($data['alert_type']);
        }
        
        // Ensure channels are in the correct format
        $channels = [];
        foreach (['email', 'sms', 'in_app'] as $channel) {
            $channels[$channel] = in_array($channel, $data['channels']);
        }
        $data['channels'] = $channels;

        $config = AlertConfiguration::create($data);

        return response()->json([
            'success' => true,
            'data' => $this->formatAlertConfig($config)
        ], 201);
    }

    /**
     * Display the specified alert configuration.
     */
    public function show(AlertConfiguration $alertConfiguration)
    {
        $this->authorize('view', $alertConfiguration);
        
        return response()->json([
            'success' => true,
            'data' => $this->formatAlertConfig($alertConfiguration)
        ]);
    }

    /**
     * Update the specified alert configuration.
     */
    public function update(Request $request, AlertConfiguration $alertConfiguration)
    {
        $this->authorize('update', $alertConfiguration);

        $validator = Validator::make($request->all(), [
            'channels' => 'sometimes|array',
            'channels.*' => 'string|in:email,sms,in_app',
            'thresholds' => 'sometimes|array',
            'is_active' => 'sometimes|boolean',
            'custom_message' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();
        
        // Update channels if provided
        if (isset($data['channels'])) {
            $channels = [];
            foreach (['email', 'sms', 'in_app'] as $channel) {
                $channels[$channel] = in_array($channel, $data['channels']);
            }
            $data['channels'] = $channels;
        }

        $alertConfiguration->update($data);

        return response()->json([
            'success' => true,
            'data' => $this->formatAlertConfig($alertConfiguration->fresh())
        ]);
    }

    /**
     * Remove the specified alert configuration.
     */
    public function destroy(AlertConfiguration $alertConfiguration)
    {
        $this->authorize('delete', $alertConfiguration);
        
        $alertConfiguration->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Alert configuration deleted successfully.'
        ]);
    }
    
    /**
     * Format alert configuration for response.
     */
    protected function formatAlertConfig(AlertConfiguration $config): array
    {
        return [
            'id' => $config->id,
            'alert_type' => $config->alert_type,
            'alert_type_display' => $this->getAlertTypeDisplayName($config->alert_type),
            'channels' => $config->channels,
            'thresholds' => $config->thresholds,
            'is_active' => $config->is_active,
            'custom_message' => $config->custom_message,
            'preferences' => $config->preferences,
            'created_at' => $config->created_at,
            'updated_at' => $config->updated_at,
        ];
    }
    
    /**
     * Get display name for alert type.
     */
    protected function getAlertTypeDisplayName(string $type): string
    {
        return match($type) {
            AlertConfiguration::TYPE_LOW_STOCK => 'Low Stock',
            AlertConfiguration::TYPE_PRICE_CHANGE => 'Price Change',
            AlertConfiguration::TYPE_DEMAND_SPIKE => 'Demand Spike',
            AlertConfiguration::TYPE_ORDER_THRESHOLD => 'Order Threshold',
            default => ucwords(str_replace('_', ' ', $type)),
        };
    }
    
    /**
     * Get available alert types and their default configurations.
     */
    public function getAlertTypes()
    {
        $types = [
            AlertConfiguration::TYPE_LOW_STOCK,
            AlertConfiguration::TYPE_PRICE_CHANGE,
            AlertConfiguration::TYPE_DEMAND_SPIKE,
            AlertConfiguration::TYPE_ORDER_THRESHOLD,
        ];
        
        $result = [];
        foreach ($types as $type) {
            $result[] = [
                'type' => $type,
                'name' => $this->getAlertTypeDisplayName($type),
                'default_thresholds' => AlertConfiguration::getDefaultThresholds($type),
                'available_channels' => [
                    AlertConfiguration::CHANNEL_EMAIL,
                    AlertConfiguration::CHANNEL_IN_APP,
                    AlertConfiguration::CHANNEL_SMS,
                ],
            ];
        }
        
        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }
}
