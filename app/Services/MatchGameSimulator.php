<?php

namespace App\Services;

use App\Models\MatchGame;
use App\Models\TeamStat;
use Illuminate\Support\Facades\DB;

class MatchGameSimulator
{
    public function __construct(
        private MatchResultCalculator $matchResultCalculator,
    )
    {
    }

    public function simulate(MatchGame $matchGame): MatchGame
    {
        try {
            DB::beginTransaction();
            $matchGameResult = $this->matchResultCalculator->calculateMatchResult($matchGame);

            TeamStat::firstOrCreate([
                'team_id' => $matchGameResult->home->id,
                'fixture_id' => $matchGameResult->fixture_id
            ])
                ->registerMatchGameResult(
                    $matchGameResult->home_team_goals,
                    $matchGameResult->away_team_goals,
                );

            TeamStat::firstOrCreate([
                'team_id' => $matchGameResult->away->id,
                'fixture_id' => $matchGameResult->fixture_id
            ])
                ->registerMatchGameResult(
                    $matchGameResult->away_team_goals,
                    $matchGameResult->home_team_goals,
                );

            DB::commit();

            return $matchGameResult;
        } catch (\Exception $exception) {
            \Log::error($exception);
            DB::rollBack();
            throw new \Exception('Something went wrong');

        }
    }
}
