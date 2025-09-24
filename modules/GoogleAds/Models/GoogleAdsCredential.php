<?php

namespace Modules\GoogleAds\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class GoogleAdsCredential extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'user_id',
        'developer_token',
        'client_id',
        'client_secret',
        'refresh_token',
        'access_token',
        'login_customer_id',
        'is_active',
    ];

    protected $hidden = [
        'developer_token',
        'client_secret',
        'refresh_token',
        'access_token',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function setDeveloperTokenAttribute($value): void
    {
        $this->attributes['developer_token'] = encrypt($value);
    }

    public function getDeveloperTokenAttribute($value): ?string
    {
        return $value ? decrypt($value) : null;
    }

    public function setClientSecretAttribute($value): void
    {
        $this->attributes['client_secret'] = encrypt($value);
    }

    public function getClientSecretAttribute($value): ?string
    {
        return $value ? decrypt($value) : null;
    }

    public function setRefreshTokenAttribute($value): void
    {
        $this->attributes['refresh_token'] = encrypt($value);
    }

    public function getRefreshTokenAttribute($value): ?string
    {
        return $value ? decrypt($value) : null;
    }

    public function setAccessTokenAttribute($value): void
    {
        $this->attributes['access_token'] = encrypt($value);
    }

    public function getAccessTokenAttribute($value): ?string
    {
        return $value ? decrypt($value) : null;
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}