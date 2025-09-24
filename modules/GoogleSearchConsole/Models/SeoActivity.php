<?php

namespace Modules\GoogleSearchConsole\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class SeoActivity extends Model
{
    protected $fillable = [
        'property_id',
        'user_id',
        'title',
        'area',
        'data_inizio',
        'data_fine',
        'stato',
        'note',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'data_inizio' => 'date',
        'data_fine' => 'date'
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(GoogleSearchConsoleProperty::class, 'property_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->where(function ($q) use ($startDate, $endDate) {
            $q->whereBetween('data_inizio', [$startDate, $endDate])
              ->orWhereBetween('data_fine', [$startDate, $endDate])
              ->orWhere(function ($q2) use ($startDate, $endDate) {
                  $q2->where('data_inizio', '<=', $startDate)
                     ->where(function ($q3) use ($endDate) {
                         $q3->where('data_fine', '>=', $endDate)
                            ->orWhereNull('data_fine');
                     });
              });
        });
    }

    public function scopeByAreas($query, array $areas)
    {
        if (empty($areas)) {
            return $query;
        }
        return $query->whereIn('area', $areas);
    }
}