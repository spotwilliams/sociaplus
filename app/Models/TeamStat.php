<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property Team $team
 * @property League $league
 */
class TeamStat extends Model
{
    protected $table = 'team_stats';
    protected $fillable = [
        'team_id',
        'fixture_id',
        'points',
        'played_matches',
        'wins',
        'draws',
        'losts',
        'scored_goals',
        'conceded_goals',
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

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function fixture(): BelongsTo
    {
        return $this->belongsTo(Fixture::class);
    }

    public function registerMatchGameResult(int $scoredGoals, int $concededGoals): void
    {
        if ($scoredGoals === $concededGoals) {
            $this->points++;
            $this->draws++;
        } elseif ($scoredGoals > $concededGoals) {
            $this->points += 3;
            $this->wins++;
        } else {
            $this->losts++;
        }

        $this->scored_goals += $scoredGoals;
        $this->conceded_goals += $concededGoals;
        $this->goals_difference = ($this->scored_goals - $this->conceded_goals);
        $this->played_matches++;

        $this->save();
    }
}
