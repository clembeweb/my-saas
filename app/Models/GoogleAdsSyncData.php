<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GoogleAdsSyncData extends Model
{
    protected $fillable = [
        'user_id',
        'account_id',
        'account_name',
        'currency_code',
        'time_zone',
        'campaigns_data',
        'keywords_data',
        'ads_data',
        'sync_token',
        'last_sync_at',
        'sync_status',
        'sync_error'
    ];

    protected $casts = [
        'campaigns_data' => 'array',
        'keywords_data' => 'array',
        'ads_data' => 'array',
        'last_sync_at' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function generateSyncToken(): string
    {
        $token = bin2hex(random_bytes(32));
        $this->update(['sync_token' => $token]);
        return $token;
    }

    public function getCampaigns(): array
    {
        return $this->campaigns_data ?? [];
    }

    public function getKeywords(): array
    {
        return $this->keywords_data ?? [];
    }

    public function getAds(): array
    {
        return $this->ads_data ?? [];
    }
}
