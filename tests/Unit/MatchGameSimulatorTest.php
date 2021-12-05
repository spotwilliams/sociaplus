<?php

namespace Tests\Unit;

use App\Models\Fixture;
use App\Models\MatchGame;
use App\Models\Team;
use App\Services\FixtureCreator;
use App\Services\MatchGameSimulator;
use App\Services\MatchResultCalculator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use Tests\TestCase;

class MatchGameSimulatorTest extends TestCase
{
    use RefreshDatabase;

    public function test_when_home_wins()
    {
        $fixture = $this->createFixture();
        /** @var MatchGame $matchGame */
        $matchGame = $fixture->matches()->first();


        $mock = $this->mock(MatchResultCalculator::class, function (MockInterface $mock) {
        });

        $matchGame->home_team_goals = 2;
        $matchGame->away_team_goals = 1;
        $mock
            ->shouldReceive('calculateMatchResult')
            ->once()
            ->withArgs([$matchGame])
            ->andReturn($matchGame);

        /** @var MatchGameSimulator $service */
        $service = $this->app->make(MatchGameSimulator::class);

        $service->simulate($matchGame);

        $this->assertEquals(3, $matchGame->home->stats->points);
        $this->assertEquals(2, $matchGame->home->stats->scored_goals);
        $this->assertEquals(1, $matchGame->home->stats->wins);
        $this->assertEquals(0, $matchGame->home->stats->losts);
        $this->assertEquals(0, $matchGame->home->stats->draws);
        $this->assertEquals(1, $matchGame->home->stats->goals_difference);


        $this->assertEquals(0, $matchGame->away->stats->points);
        $this->assertEquals(1, $matchGame->away->stats->scored_goals);
        $this->assertEquals(0, $matchGame->away->stats->wins);
        $this->assertEquals(1, $matchGame->away->stats->losts);
        $this->assertEquals(0, $matchGame->away->stats->draws);
        $this->assertEquals(-1, $matchGame->away->stats->goals_difference);

    }

    public function test_when_away_wins()
    {
        $fixture = $this->createFixture();
        /** @var MatchGame $matchGame */
        $matchGame = $fixture->matches()->first();


        $mock = $this->mock(MatchResultCalculator::class, function (MockInterface $mock) {
        });

        $matchGame->home_team_goals = 1;
        $matchGame->away_team_goals = 2;
        $mock
            ->shouldReceive('calculateMatchResult')
            ->once()
            ->withArgs([$matchGame])
            ->andReturn($matchGame);

        /** @var MatchGameSimulator $service */
        $service = $this->app->make(MatchGameSimulator::class);

        $service->simulate($matchGame);

        $this->assertEquals(3, $matchGame->away->stats->points);
        $this->assertEquals(2, $matchGame->away->stats->scored_goals);
        $this->assertEquals(1, $matchGame->away->stats->wins);
        $this->assertEquals(0, $matchGame->away->stats->losts);
        $this->assertEquals(0, $matchGame->away->stats->draws);
        $this->assertEquals(1, $matchGame->away->stats->goals_difference);


        $this->assertEquals(0, $matchGame->home->stats->points);
        $this->assertEquals(1, $matchGame->home->stats->scored_goals);
        $this->assertEquals(0, $matchGame->home->stats->wins);
        $this->assertEquals(1, $matchGame->home->stats->losts);
        $this->assertEquals(0, $matchGame->home->stats->draws);
        $this->assertEquals(-1, $matchGame->home->stats->goals_difference);

    }

    public function test_when_draw()
    {
        $fixture = $this->createFixture();
        /** @var MatchGame $matchGame */
        $matchGame = $fixture->matches()->first();


        $mock = $this->mock(MatchResultCalculator::class, function (MockInterface $mock) {
        });

        $matchGame->home_team_goals = 2;
        $matchGame->away_team_goals = 2;
        $mock
            ->shouldReceive('calculateMatchResult')
            ->once()
            ->withArgs([$matchGame])
            ->andReturn($matchGame);

        /** @var MatchGameSimulator $service */
        $service = $this->app->make(MatchGameSimulator::class);

        $service->simulate($matchGame);

        $this->assertEquals(1, $matchGame->away->stats->points);
        $this->assertEquals(2, $matchGame->away->stats->scored_goals);
        $this->assertEquals(0, $matchGame->away->stats->wins);
        $this->assertEquals(0, $matchGame->away->stats->losts);
        $this->assertEquals(1, $matchGame->away->stats->draws);
        $this->assertEquals(0, $matchGame->away->stats->goals_difference);


        $this->assertEquals(1, $matchGame->home->stats->points);
        $this->assertEquals(2, $matchGame->home->stats->scored_goals);
        $this->assertEquals(0, $matchGame->home->stats->wins);
        $this->assertEquals(0, $matchGame->home->stats->losts);
        $this->assertEquals(1, $matchGame->home->stats->draws);
        $this->assertEquals(0, $matchGame->home->stats->goals_difference);

    }
    private function createFixture(): Fixture
    {
        /** @var FixtureCreator $service */
        $service = $this->app->make(FixtureCreator::class);

        return $service->schedule([
            Team::create(['name' => 'A']),
            Team::create(['name' => 'B']),
            Team::create(['name' => 'C']),
            Team::create(['name' => 'D']),
        ]);
    }
}
