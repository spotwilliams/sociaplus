<?php

namespace App\Services;

use App\Models\League;
use Illuminate\Support\Collection;

class ForecastingService
{
    public function __construct(
        private ForecastCalculator $forecastCalculator,
    )
    {
    }

    public function calculate(League $league): Collection
    {
        return $this->forecastCalculator->calculateWiningPercents($league);
    }
}
