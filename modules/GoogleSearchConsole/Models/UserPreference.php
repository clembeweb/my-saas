<?php

namespace Modules\GoogleSearchConsole\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class UserPreference extends Model
{
    protected $table = 'gsc_user_preferences';

    protected $fillable = [
        'user_id',
        'theme',
        'font_size',
        'series_colors',
        'area_colors',
        'preset',
        'settings_json'
    ];

    protected $casts = [
        'series_colors' => 'array',
        'area_colors' => 'array',
        'settings_json' => 'array',
        'font_size' => 'integer'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function getDefaultPreferences(): array
    {
        return [
            'theme' => 'light',
            'font_size' => 14,
            'series_colors' => [
                'clicks' => '#00bcd4',
                'impressions' => '#f44336'
            ],
            'area_colors' => [
                'SEO Tool' => '#4caf50',
                'Magazine' => '#ff9800',
                'SEO On-page' => '#2196f3',
                'SEO Off-page' => '#9c27b0',
                'Altro' => '#607d8b'
            ],
            'preset' => 'default',
            'settings_json' => []
        ];
    }

    public function getSeriesColorsAttribute($value): array
    {
        return $value ? json_decode($value, true) : self::getDefaultPreferences()['series_colors'];
    }

    public function getAreaColorsAttribute($value): array
    {
        return $value ? json_decode($value, true) : self::getDefaultPreferences()['area_colors'];
    }
}