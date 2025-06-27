<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailySale extends Model
{
    protected $fillable = [
        'product_id',
        'date',
        'quantity_sold',
        'revenue',
    ];

    protected $casts = [
        'date' => 'date',
        'quantity_sold' => 'integer',
        'revenue' => 'decimal:2',
    ];

    /**
     * Get the product that owns the daily sale.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
