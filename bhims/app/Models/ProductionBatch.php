<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductionBatch extends Model
{
    protected $fillable = [
        'batch_number',
        'recipe_id',
        'user_id',
        'production_date',
        'expiry_date',
        'quantity_produced',
        'notes',
        'status',
    ];

    protected $casts = [
        'production_date' => 'date',
        'expiry_date' => 'date',
        'quantity_produced' => 'decimal:2',
    ];

    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
