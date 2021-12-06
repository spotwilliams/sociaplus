<?php

namespace App\Services;

use App\Models\MatchGame;

class StrengthParameters
{
    const AVG_HOME_GOALS = 1.35;
    const AVG_AWAY_GOALS = 1.36;

    public static function averageHomeGoals(): float
    {
        return (self::AVG_HOME_GOALS + MatchGame::average('home_team_goals')) / 2;
    }

    public static function averageAwayGoals(): float
    {
        return (self::AVG_HOME_GOALS + MatchGame::average('away_team_goals')) / 2;
    }
}
