<?php

namespace Tests\Unit;

use App\Models\League;
use App\Models\MatchGame;
use App\Models\Team;
use App\Models\TeamStat;
use App\Services\LeagueService;
use App\Services\MatchResultCalculator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeagueTest extends TestCase
{
    use RefreshDatabase;

    public function test_league_is_created_with_fixture_completed()
    {
        $league = $this->generateLeague();

        $this->assertEquals(12, $league->fixture->matches()->count());
        $this->assertEquals(1, $league->current_week);
        $this->assertEquals(0, $league->fixture->matches()->where('played', true)->count());
    }

    public function test_simulate_one_week()
    {
        $this->app->bind(MatchResultCalculator::class, function($app) {
            return $this->generateDummyCalculator();
        });
        /** @var LeagueService $service */
        $service = $this->app->make(LeagueService::class);

        $league = $this->generateLeague($service);


        $this->assertEquals(12, $league->fixture->matches()->count());
        $this->assertEquals(1, $league->current_week);
        $this->assertEquals(0, $league->fixture->matches()->where('played', true)->count());

        $service->simulateWeek($league);
        $this->assertEquals(2, $league->current_week);
        $this->assertEquals(2, $league->fixture->matches()->where('played', true)->count());
        $this->assertEquals(4, TeamStat::all()->count());
        $this->assertEquals(4, TeamStat::where('played_matches', 1)->count());
    }

    private function generateLeague(LeagueService $service = null): League
    {
        if (!$service) {

            /** @var LeagueService $service */
            $service = $this->app->make(LeagueService::class);
        }

        return $service->startNewSimulation(Team::all());
    }

    private function generateDummyCalculator()
    {
        return new class implements MatchResultCalculator {
            public function calculateMatchResult(MatchGame $matchGame): MatchGame
            {
                $matchGame->home_team_goals = 2;
                $matchGame->away_team_goals = 1;
                $matchGame->played = true;
                $matchGame->save();

                return $matchGame;
            }
        };
    }

}
