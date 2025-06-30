<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class AlertConfiguration extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'alert_type',
        'channels',
        'thresholds',
        'is_active',
        'custom_message',
        'preferences'
    ];

    protected $casts = [
        'channels' => 'array',
        'thresholds' => 'array',
        'preferences' => 'array',
        'is_active' => 'boolean',
    ];

    // Alert types
    const TYPE_LOW_STOCK = 'low_stock';
    const TYPE_PRICE_CHANGE = 'price_change';
    const TYPE_DEMAND_SPIKE = 'demand_spike';
    const TYPE_ORDER_THRESHOLD = 'order_threshold';

    // Channel types
    const CHANNEL_EMAIL = 'email';
    const CHANNEL_SMS = 'sms';
    const CHANNEL_IN_APP = 'in_app';

    /**
     * Get the user that owns the alert configuration.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get default thresholds for alert types
     */
    public static function getDefaultThresholds(string $alertType): array
    {
        return match($alertType) {
            self::TYPE_LOW_STOCK => [
                'warning_level' => 20, // percentage
                'critical_level' => 10, // percentage
            ],
            self::TYPE_PRICE_CHANGE => [
                'percentage_change' => 10, // percentage
                'time_frame' => 24, // hours
            ],
            self::TYPE_DEMAND_SPIKE => [
                'percentage_increase' => 50, // percentage
                'time_frame' => 24, // hours
            ],
            self::TYPE_ORDER_THRESHOLD => [
                'minimum_quantity' => 5,
            ],
            default => [],
        };
    }
}
