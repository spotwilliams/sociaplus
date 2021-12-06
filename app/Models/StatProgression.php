<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StatProgression extends Model
{
    protected $table = 'stat_progressions';
    protected $fillable = [
        'stat_id',
        'points',
        'played_matches',
        'wins',
        'draws',
        'losts',
        'scored_goals',
        'conceded_goals',
        'goals_difference',
        'week',
    ];

    protected $attributes = [
        'points' => 0,
        'played_matches' => 0,
        'wins' => 0,
        'draws' => 0,
        'losts' => 0,
        'scored_goals' => 0,
        'conceded_goals' => 0,
        'goals_difference' => 0,
    ];


    public function stat(): BelongsTo
    {
        return $this->belongsTo(TeamStat::class);
    }
}
