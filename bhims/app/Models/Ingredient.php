<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ingredient extends Model
{
    protected $fillable = [
        'name',
        'description',
        'category_id',
        'supplier_id',
        'unit_of_measure',
        'current_stock',
        'minimum_stock',
        'unit_price',
        'is_active',
        'low_stock_notified',
        'last_stock_notification_at',
    ];

    protected $casts = [
        'current_stock' => 'decimal:2',
        'minimum_stock' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'is_active' => 'boolean',
        'low_stock_notified' => 'boolean',
        'last_stock_notification_at' => 'datetime',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function recipes(): BelongsToMany
    {
        return $this->belongsToMany(Recipe::class, 'ingredient_recipe')
            ->withPivot('quantity', 'unit_of_measure', 'notes')
            ->withTimestamps();
    }

    /**
     * Get all stock movements for the ingredient.
     */
    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    /**
     * Get all alerts for this ingredient.
     */
    public function alerts()
    {
        return $this->hasMany(Alert::class, 'metadata->ingredient_id');
    }

    /**
     * Get the supplier that provides this ingredient.
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    
    /**
     * Get all purchase order items for the ingredient.
     */
    public function purchaseOrderItems()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }
}
