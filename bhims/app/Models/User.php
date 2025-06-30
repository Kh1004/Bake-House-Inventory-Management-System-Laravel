<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\CausesActivity;
use App\Models\Role;
use App\Models\PurchaseOrder;
use App\Models\ProductionBatch;
use App\Models\StockMovement;
use App\Models\ProductStockMovement;
use App\Models\AlertConfiguration;

class User extends Authenticatable
{
    use HasFactory, Notifiable, CausesActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    /**
     * The roles that are allowed in the application.
     *
     * @var array
     */
    public const ROLES = [
        'admin' => 'Administrator',
        'manager' => 'Manager',
        'staff' => 'Staff',
    ];

    /**
     * The default role for new users.
     *
     * @var string
     */
    public const DEFAULT_ROLE = 'staff';
    
    /**
     * The attributes that should be appended to the model's array form.
     *
     * @var array
     */
    protected $appends = ['role_name'];
    
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $with = [];

    /**
     * Get the role that owns the user.
     */
    /**
     * Check if user has a specific role.
     *
     * @param string $role
     * @return bool
     */
    public function hasRole($role)
    {
        return $this->role === $role;
    }

    /**
     * Check if user has admin role.
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    /**
     * Check if user has manager role.
     *
     * @return bool
     */
    public function isManager()
    {
        return $this->hasRole('manager');
    }

    /**
     * Check if user has staff role.
     *
     * @return bool
     */
    public function isStaff()
    {
        return $this->hasRole('staff');
    }

    /**
     * Get the user's role name.
     *
     * @return string
     */
    public function getRoleNameAttribute()
    {
        return self::ROLES[$this->role] ?? 'Unknown';
    }

    /**
     * Get the purchase orders for the user.
     */
    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    /**
     * Get the production batches for the user.
     */
    public function productionBatches()
    {
        return $this->hasMany(ProductionBatch::class);
    }

    /**
     * Get the stock movements for the user.
     */
    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }
    
    /**
     * Get all of the alert configurations for the user.
     */
    public function alertConfigurations()
    {
        return $this->hasMany(AlertConfiguration::class);
    }

    /**
     * Get the product stock movements for the user.
     */
    public function productStockMovements()
    {
        return $this->hasMany(ProductStockMovement::class);
    }
}
