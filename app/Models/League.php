<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property Fixture $fixture
 */
class League extends Model
{
    protected $fillable = [
        'current_week',
        'fixture_id',
    ];
    protected $attributes = [
        'current_week' => 1,
    ];

    public function fixture(): BelongsTo
    {
        return $this->belongsTo(Fixture::class);
    }

    public function advanceWeek(): void
    {
        $this->current_week++;
        $this->save();
    }
}
