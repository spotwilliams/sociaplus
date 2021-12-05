<?php

namespace App\Services;

use App\Models\League;

class ForecastingService
{
    public function __construct(
        private ForecastCalculator $forecastCalculator,
    )
    {
    }

    public function calculate(League $league): array
    {
        return $this->forecastCalculator->calculateWiningPercents($league);
    }
}
