<?php

namespace App\Services;

use App\Models\MatchGame;

class CalculateGoalBasedOnStrengths implements MatchResultCalculator
{

    // I've decided to use approach
    // https://themathbetman.com/2021/04/22/arsenal-vs-everton-a-poisson-distribution-worked-example/
    public function calculateMatchResult(MatchGame $matchGame): MatchGame
    {
        $matchGame->home_team_goals = intval($matchGame->home->home_attacking_strength * $matchGame->away->away_defensive_strength * StrengthParameters::averageHomeGoals()) + $this->scoreOneExtraGoal();
        $matchGame->away_team_goals = intval($matchGame->away->away_attacking_strength * $matchGame->home->home_defensive_strength * StrengthParameters::averageAwayGoals()) + $this->scoreOneExtraGoal();

        $matchGame->played = true;
        $matchGame->save();

        return $matchGame;
    }

    private function scoreOneExtraGoal(): bool
    {
        $chancesToScore =  StrengthParameters::AVG_HOME_GOALS * 100 + StrengthParameters::AVG_AWAY_GOALS * 100;

        return rand(1, $chancesToScore) > rand(1, $chancesToScore);
    }
}
