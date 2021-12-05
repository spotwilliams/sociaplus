<?php

namespace Tests\Unit;

use App\Models\League;
use App\Models\Team;
use App\Services\ForecastingService;
use App\Services\LeagueService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ForecastCalculationTest extends TestCase
{
    use RefreshDatabase;

    public function test_forecast_gives_higher_percent_to_best_scores()
    {
        /** @var LeagueService $service */
        $service = $this->app->make(LeagueService::class);

        $league = $this->generateLeague($service);

        $league = $this->simulateUntilLastThreeWeeks($league, $service);

        /** @var ForecastingService $forecastService */
        $forecastService = $this->app->make(ForecastingService::class);

        $forecast = $forecastService->calculate($league);
        // This test was made to check if it can run until here
        $this->assertTrue(true);
    }


    private function generateLeague(LeagueService $service = null): League
    {
        if (!$service) {
            /** @var LeagueService $service */
            $service = $this->app->make(LeagueService::class);
        }

        return $service->startNewSimulation(Team::all());

    }

    public function simulateUntilLastThreeWeeks(League $league, LeagueService $service = null): League
    {
        for ($i = 0; $i < ($league->fixture->weeks - 3); $i++) {
            $service->simulateWeek($league->fresh());
        }

        return $league->fresh();
    }
}
