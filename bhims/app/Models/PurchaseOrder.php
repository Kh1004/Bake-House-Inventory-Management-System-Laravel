<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchaseOrder extends Model
{
    protected $fillable = [
        'po_number',
        'supplier_id',
        'user_id',
        'order_date',
        'expected_delivery_date',
        'status',
        'notes',
        'total_amount',
        'item_count',
    ];

    protected $casts = [
        'order_date' => 'date',
        'expected_delivery_date' => 'date',
        'total_amount' => 'decimal:2',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }
    
    /**
     * Get the number of items in the purchase order.
     * Uses the cached item_count if available, otherwise counts the items.
     *
     * @return int
     */
    public function getItemCountAttribute($value)
    {
        if ($value !== null) {
            return $value;
        }
        
        // If item_count is not set, count the items and update the cached value
        $count = $this->items()->count();
        $this->update(['item_count' => $count]);
        
        return $count;
    }
}
