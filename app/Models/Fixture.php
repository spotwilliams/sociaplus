<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Fixture extends Model
{
    protected $table = 'fixtures';
    protected $fillable = [
        'weeks'
    ];

    protected $attributes = [
        'weeks' => 0,
    ];

    public $timestamps = false;

    public function addMatchesOfWeek(\Illuminate\Support\Collection $matches)
    {
        $this->weeks++;

        foreach ($matches as $match) {
            // Try to find any previous math for these two teams
            $previouslyPlayed = $this->findMatch($match->first(), $match->last());
            // if teams already played just switch home and away properly
            $homeId = optional($previouslyPlayed)->away_team_id ?? $match->first()->id;
            $awayId = optional($previouslyPlayed)->home_team_id ?? $match->last()->id;

            $matchGame = MatchGame::create([
                'week' => $this->weeks,
                'home_team_id' => $homeId,
                'away_team_id' => $awayId,
                'fixture_id' => $this->id,
            ]);
            $this->matches()->save($matchGame);
        }

    }

    public function matches(): HasMany
    {
        return $this->hasMany(MatchGame::class);
    }

    public function matchesOf(Team $team): Collection
    {
        return $this
            ->matches()
            ->where('home_team_id', $team->id)
            ->orWhere('away_team_id', $team->id)
            ->get();
    }

    public function findMatch(Team $teamA, Team $teamB): ?MatchGame
    {
        return $this->matches()
            ->get()
            ->first(function (MatchGame $matchGame) use ($teamA, $teamB) {
                if ($matchGame->home_team_id === $teamA->id && $matchGame->away_team_id === $teamB->id)
                    return $matchGame;
                if ($matchGame->home_team_id === $teamB->id && $matchGame->away_team_id === $teamA->id)
                    return $matchGame;

                return null;
            })
            ;

    }
}
