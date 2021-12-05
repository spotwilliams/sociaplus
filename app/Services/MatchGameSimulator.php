<?php

namespace App\Services;

use App\Models\MatchGame;
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

            $matchGameResult->home
                ->stats()
                ->firstOrCreate()
                ->registerMatchGameResult(
                    $matchGameResult->home_team_goals,
                    $matchGameResult->away_team_goals,
                );

            $matchGameResult->away
                ->stats()
                ->firstOrCreate()->registerMatchGameResult(
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
