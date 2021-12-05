<?php

namespace App\Services;

use App\Models\MatchGame;

interface MatchResultCalculator
{
    public function calculateMatchResult(MatchGame $matchGame): MatchGame;
}
