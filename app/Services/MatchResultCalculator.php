<?php

namespace App\Services;

use App\Models\MatchGame;

interface MatchResultCalculator
{
    /**
     * This method should be implemented to calculate if an event will be considered a goal
     * You can apply any sort of factors to determine if a goal was made
     */
    public function calculateMatchResult(MatchGame $matchGame): MatchGame;
}
