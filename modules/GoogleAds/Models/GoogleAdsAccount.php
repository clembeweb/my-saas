<?php

namespace Modules\GoogleAds\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class GoogleAdsAccount extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'customer_id',
        'descriptive_name',
        'currency_code',
        'time_zone',
        'manager_customer_id',
        'is_manager_account',
        'is_active',
        'last_sync_at',
    ];

    protected $casts = [
        'is_manager_account' => 'boolean',
        'is_active' => 'boolean',
        'last_sync_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function managerAccount(): BelongsTo
    {
        return $this->belongsTo(self::class, 'manager_customer_id', 'customer_id');
    }

    public function subAccounts()
    {
        return $this->hasMany(self::class, 'manager_customer_id', 'customer_id');
    }
}