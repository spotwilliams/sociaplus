<?php

namespace App\Http\Controllers;

use App\Models\MatchGame;
use App\Models\StatProgression;
use App\Models\Team;
use App\Services\LeagueService;

class LeagueController
{
    public function index(LeagueService $leagueService, Team $team)
    {
        $statsByWeek = StatProgression::with(['stat.team'])
            ->orderBy('week', 'DESC')
            ->orderBy('points', 'DESC')
            ->get()
            ->groupBy('week');

        $matchesByWeek = MatchGame::with(['home', 'away'])
            ->orderBy('week', 'DESC')
            ->get()
            ->groupBy('week');

        return view('index', [
            'league' => $leagueService->getCurrentSimulation(),
            'teams' => $team::with('stats')->get(),
            'statsByWeek' => $statsByWeek,
            'matchesByWeek' => $matchesByWeek,
        ]);
    }

    public function simulate(LeagueService $leagueService)
    {
        $league = $leagueService->getCurrentSimulation();
        if (!$league->isFinished()) {
            $leagueService->simulateWeek($league);
        }

        return redirect('/');
    }
}
