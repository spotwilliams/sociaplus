<?php

namespace App\Services;

use App\Models\League;

interface ForecastCalculator
{
    public function calculateWiningPercents(League $league): array;
}
