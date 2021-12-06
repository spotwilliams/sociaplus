<?php

namespace App\Services;

use App\Models\League;
use Illuminate\Support\Collection;

interface ForecastCalculator
{
    public function calculateWiningPercents(League $league): Collection;
}
