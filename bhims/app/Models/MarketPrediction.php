<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MarketPrediction extends Model
{
    protected $fillable = [
        'product_id',
        'prediction_date',
        'predicted_demand',
        'confidence_interval_lower',
        'confidence_interval_upper',
        'historical_data',
        'prediction_metrics',
        'notes'
    ];

    protected $casts = [
        'prediction_date' => 'date',
        'predicted_demand' => 'decimal:2',
        'confidence_interval_lower' => 'decimal:2',
        'confidence_interval_upper' => 'decimal:2',
        'historical_data' => 'array',
        'prediction_metrics' => 'array',
    ];

    /**
     * Get the product associated with the prediction.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
