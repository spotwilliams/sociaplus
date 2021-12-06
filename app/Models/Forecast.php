<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Forecast extends Model
{
    protected $fillable = [
        'team_id',
        'fixture_id',
        'wining_percent',
        'week'
    ];

    protected $attributes = [
        'wining_percent' => 0.0,
        'week' => 1,
    ];

    protected $casts = [
        'wining_percent' => 'float',
        'week' => 'integer',
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function fixture(): BelongsTo
    {
        return $this->belongsTo(Fixture::class);
    }
}
