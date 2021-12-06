<?php

namespace App\Http\Controllers;

use App\Models\Forecast;
use App\Models\MatchGame;
use App\Models\StatProgression;
use App\Models\Team;
use App\Services\ForecastingService;
use App\Services\LeagueService;
use Illuminate\Http\Request;

class LeagueController
{
    public function index(LeagueService $leagueService, Team $team)
    {
        $league = $leagueService->getCurrentSimulation();
        $statsByWeek = StatProgression::with(['stat.team'])
            ->whereHas('stat', function ($query) use ($league) {
                $query->where('fixture_id', $league->fixture_id);
            })
            ->orderBy('week', 'DESC')
            ->orderBy('points', 'DESC')
            ->get()
            ->groupBy('week');

        $matchesByWeek = MatchGame::with(['home', 'away'])
            ->where('fixture_id', $league->fixture_id)
            ->orderBy('week', 'DESC')
            ->get()
            ->groupBy('week');

        $estimationByWeeks = Forecast::with('team')
            ->where('fixture_id', $league->fixture_id)
            ->orderBy('week', 'DESC')
            ->orderBy('wining_percent', 'DESC')
            ->get()
            ->groupBy('week');

        return view('index', [
            'league' => $league,
            'teams' => $team::with('stats')->get(),
            'statsByWeek' => $statsByWeek,
            'matchesByWeek' => $matchesByWeek,
            'estimationByWeeks' => $estimationByWeeks,
        ]);
    }

    public function simulate(Request $request, LeagueService $leagueService, ForecastingService $forecastingService)
    {
        $league = $leagueService->getCurrentSimulation($request->input('from_scratch', false));
        if (!$league->isFinished()) {
            $leagueService->simulateWeek($league);

            if ($league->current_week >= config('league.estimation_starts_after_week') and $league->current_week !== $league->fixture->weeks) {
                $forecastingService->calculate($league);
            }
        }

        return redirect('/');
    }

    public function simulateAll(LeagueService $leagueService, ForecastingService $forecastingService)
    {
        $league = $leagueService->getCurrentSimulation();
        while (! $league->isFinished()) {
            $leagueService->simulateWeek($league);

            if ($league->current_week >= config('league.estimation_starts_after_week') and $league->current_week !== $league->fixture->weeks) {
                $forecastingService->calculate($league);
            }
        }

        return redirect('/');
    }
}
