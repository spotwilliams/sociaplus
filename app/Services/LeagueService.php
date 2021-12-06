<?php

namespace App\Services;

use App\Models\League;
use App\Models\MatchGame;
use App\Models\Team;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class LeagueService
{
    public function __construct(
        private MatchGameSimulator $matchGameSimulator,
        private FixtureCreator     $fixtureCreator,
    )
    {
    }

    public function simulateWeek(League $league): League
    {
        try {
            DB::beginTransaction();
            $matches = $league
                ->fixture
                ->matches()
                ->where('week', $league->current_week + 1)
                ->get();

            /** @var MatchGame $match */
            foreach ($matches as $match) {
                $this->matchGameSimulator->simulate($match);
            }

            $league->advanceWeek();

            DB::commit();
            return $league;

        } catch (\Exception $exception) {
            \Log::error($exception);
            DB::rollBack();
            throw new \Exception('Something went wrong');
        }
    }

    public function startNewSimulation(Collection $teams): League
    {
        try {
            DB::beginTransaction();
            $fixture = $this->fixtureCreator->schedule($teams);

            $league = League::create([
                'fixture_id' => $fixture->id,
            ]);

            DB::commit();

            return $league;
        } catch (\Exception $exception) {
            \Log::error($exception);
            DB::rollBack();
            throw new \Exception('Something went wrong');
        }
    }

    public function getCurrentSimulation(): League
    {
        $league = League::orderBy('created_at', 'DESC')->first();
        if (! $league) {
            $league = $this->startNewSimulation(Team::all());
        }

        return $league;
    }
}
