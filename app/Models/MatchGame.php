<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property Team $home
 * @property Team $away
 */
class MatchGame extends Model
{
    protected $table = 'match_games';

    protected $fillable = [
        'week',
        'home_team_id',
        'away_team_id',
        'fixture_id',
        'home_team_goals',
        'away_team_goals',
        'played',
    ];

    protected $attributes = [
        'home_team_goals' => 0,
        'away_team_goals' => 0,
        'played' => false,
    ];

    public $timestamps = false;

    public function fixture(): BelongsTo
    {
        return $this->belongsTo(Fixture::class);
    }

    public function home(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'home_team_id');
    }

    public function away(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'away_team_id');
    }

    public function playAgainst(Team $team): ?Team
    {
        // Is the team provided part of this match?
        if (in_array($team->id, [$this->home_team_id, $this->away_team_id])) {
            return $team->id !== $this->home_team_id ? $this->home()->first() : $this->away()->first();
        }
        return throw new \InvalidArgumentException('The team is not part of this game.');
    }

    public function playedAtHome(Team $team)
    {
        if (in_array($team->id, [$this->home_team_id, $this->away_team_id])) {
            return $team->id === $this->home_team_id;
        }
        return throw new \InvalidArgumentException('The team is not part of this game.');
    }
}
