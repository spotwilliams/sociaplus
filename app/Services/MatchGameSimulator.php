<?php

namespace App\Services;

use App\Models\MatchGame;
use App\Models\StatProgression;
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

            $homeTeamStat = TeamStat::firstOrCreate([
                'team_id' => $matchGameResult->home->id,
                'fixture_id' => $matchGameResult->fixture_id
            ])
                ->registerMatchGameResult(
                    $matchGameResult->home_team_goals,
                    $matchGameResult->away_team_goals,
                );

            StatProgression::create([
                'stat_id' => $homeTeamStat->id,
                'points' => $homeTeamStat->points,
                'played_matches' => $homeTeamStat->played_matches,
                'wins' => $homeTeamStat->wins,
                'draws' => $homeTeamStat->draws,
                'losts' => $homeTeamStat->losts,
                'scored_goals' => $homeTeamStat->scored_goals,
                'conceded_goals' => $homeTeamStat->conceded_goals,
                'goals_difference' => $homeTeamStat->goals_difference,
                'week' => $matchGameResult->week,
            ]);

            $awayTeamStat = TeamStat::firstOrCreate([
                'team_id' => $matchGameResult->away->id,
                'fixture_id' => $matchGameResult->fixture_id
            ])
                ->registerMatchGameResult(
                    $matchGameResult->away_team_goals,
                    $matchGameResult->home_team_goals,
                );

            StatProgression::create([
                'stat_id' => $awayTeamStat->id,
                'points' => $awayTeamStat->points,
                'played_matches' => $awayTeamStat->played_matches,
                'wins' => $awayTeamStat->wins,
                'draws' => $awayTeamStat->draws,
                'losts' => $awayTeamStat->losts,
                'scored_goals' => $awayTeamStat->scored_goals,
                'conceded_goals' => $awayTeamStat->conceded_goals,
                'goals_difference' => $awayTeamStat->goals_difference,
                'week' => $matchGameResult->week,
            ]);
            DB::commit();

            return $matchGameResult;
        } catch (\Exception $exception) {
            \Log::error($exception);
            DB::rollBack();
            throw new \Exception('Something went wrong');

        }
    }
}
