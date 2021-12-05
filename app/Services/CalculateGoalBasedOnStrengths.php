<?php

namespace App\Services;

use App\Models\MatchGame;

class CalculateGoalBasedOnStrengths implements MatchResultCalculator
{
    public function __construct(
        private $avgHomeGoals,
        private $avgAwayGoals,
    )
    {
    }

    // I've decided to use approach
    // https://themathbetman.com/2021/04/22/arsenal-vs-everton-a-poisson-distribution-worked-example/
    public function calculateMatchResult(MatchGame $matchGame): MatchGame
    {
        $matchGame->home_team_goals = intval($matchGame->home->home_attacking_strength * $matchGame->away->away_defensive_strength * $this->avgHomeGoals);
        $matchGame->away_team_goals = intval($matchGame->away->away_attacking_strength * $matchGame->home->home_defensive_strength * $this->avgAwayGoals);

        $matchGame->save();

        return $matchGame;
    }
}
