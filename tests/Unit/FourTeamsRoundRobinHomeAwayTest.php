<?php

namespace Tests\Unit;

use App\Models\MatchGame;
use App\Models\Team;
use App\Services\FixtureCreator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Scenario where you have 4 teams in a based Round Robin home-away format
 */
class FourTeamsRoundRobinHomeAwayTest extends TestCase
{
    use RefreshDatabase;

    public function test_should_be_6_weeks()
    {
        $service = new FixtureCreator();
        $teams = [
            Team::create(['name' => 'A']),
            Team::create(['name' => 'B']),
            Team::create(['name' => 'C']),
            Team::create(['name' => 'D']),
        ];
        $schedule = $service->schedule($teams);

        $this->assertEquals(6, $schedule->weeks);
    }

    public function test_should_be_12_matches()
    {
        $service = new FixtureCreator();
        $teams = [
            Team::create(['name' => 'A']),
            Team::create(['name' => 'B']),
            Team::create(['name' => 'C']),
            Team::create(['name' => 'D']),
        ];
        $schedule = $service->schedule($teams);

        $this->assertEquals(12, $schedule->matches->count());
    }

    public function test_every_team_should_play_twice_with_each_other()
    {
        $service = new FixtureCreator();
        $teams = [
            Team::create(['name' => 'A']),
            Team::create(['name' => 'B']),
            Team::create(['name' => 'C']),
            Team::create(['name' => 'D']),
        ];
        $schedule = $service->schedule($teams);

        foreach ($teams as $team) {
            $matchesOfTeam = $schedule->matchesOf($team);
            $counter = [];
            /** @var MatchGame $match */
            foreach ($matchesOfTeam as $match) {
                $rivalName = $match->playAgainst($team)->name;
                $count = $counter[$rivalName] ?? 0;
                $counter[$rivalName] = ++$count;
            }

            $this->assertEquals(3, count($counter)); // 3 rivals
            foreach ($counter as $rival => $matches) {
                $this->assertEquals(2, $matches);
            }
        }
    }

    public function test_matches_with_same_teams_should_be_home_based_equally()
    {
        $service = new FixtureCreator();
        $teams = [
            Team::create(['name' => 'A']),
            Team::create(['name' => 'B']),
            Team::create(['name' => 'C']),
            Team::create(['name' => 'D']),
        ];
        $schedule = $service->schedule($teams);

        foreach ($teams as $team) {
            $matchesOfTeam = $schedule->matchesOf($team);
            $counter = [];
            /** @var MatchGame $match */
            foreach ($matchesOfTeam as $match) {
                $rivalName = $match->playAgainst($team)->name;
                $playedAs = $match->playedAtHome($team) ? 'home' : 'away';
                $count = $counter[$rivalName][$playedAs] ?? 0;
                $counter[$rivalName][$playedAs] = ++$count;
            }

            $this->assertEquals(3, count($counter)); // 3 rivals
            foreach ($counter as $rival => $matches) {
                $this->assertEquals(2, count($matches));
                $this->assertEquals(1, $matches['home']);
                $this->assertEquals(1, $matches['away']);
            }
        }
    }
}
