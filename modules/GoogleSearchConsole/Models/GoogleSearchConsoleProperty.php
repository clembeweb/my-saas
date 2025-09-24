<?php

namespace Modules\GoogleSearchConsole\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;

class GoogleSearchConsoleProperty extends Model
{
    protected $fillable = [
        'user_id',
        'site_url',
        'property_type',
        'permission_level',
        'verification_method',
        'is_verified',
        'access_token',
        'refresh_token',
        'token_expires_at'
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'token_expires_at' => 'datetime'
    ];

    protected $hidden = [
        'access_token',
        'refresh_token'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(SeoActivity::class, 'property_id');
    }

    public function getDecryptedAccessTokenAttribute(): ?string
    {
        return $this->access_token ? decrypt($this->access_token) : null;
    }

    public function getDecryptedRefreshTokenAttribute(): ?string
    {
        return $this->refresh_token ? decrypt($this->refresh_token) : null;
    }

    public function setAccessTokenAttribute(?string $value): void
    {
        $this->attributes['access_token'] = $value ? encrypt($value) : null;
    }

    public function setRefreshTokenAttribute(?string $value): void
    {
        $this->attributes['refresh_token'] = $value ? encrypt($value) : null;
    }
}