@extends('layout')

@section('content')
    <form method="POST" action="{{route('simulate')}}">
        <h3 style="display: inline-block">Welcome to Premier League - Simulation #{{$league->id}} </h3>
        @csrf
        <input type="hidden" name="from_scratch" value="true">
        <button type="submit" class="btn btn-primary">Start Simulation</button>
    </form>

    @if($league->current_week === 0)

    @endif
    @if(!$league->isFinished())
        <div class="btn-group">

            <form method="POST" action="{{route('simulate_all')}}">
                @csrf
                <button type="submit" class="btn btn-success mr-1">Play All</button>
            </form>
            <form method="POST" action="{{route('simulate')}}">
                @csrf
                <button type="submit" class="btn btn-primary">Next Week</button>
            </form>
        </div>
    @endif

    @foreach($statsByWeek as $week => $stWeek)
        <div class="row mt-5">
            <div class="col-12">
                <div class="alert alert-dark" role="alert">
                    Week <span class="badge badge-info">{{$week}}</span>
                </div>
            </div>
            <!--
             Table of positions
             -->
            <div class="col-6 border-right">
                <table class="table table-hover">
                    <thead class="table-dark">
                    <tr>
                        <td>Teams</td>
                        <td>Pts</td>
                        <td>P</td>
                        <td>W</td>
                        <td>D</td>
                        <td>L</td>
                        <td>GD</td>
                    </tr>
                    </thead>
                    <tbody class="table-striped">
                    @foreach($stWeek as $teamStWeek)
                        <tr>
                            <td>{{$teamStWeek->stat->team->name}}</td>
                            <td>{{$teamStWeek->points}}</td>
                            <td>{{$teamStWeek->played_matches}}</td>
                            <td>{{$teamStWeek->wins}}</td>
                            <td>{{$teamStWeek->draws}}</td>
                            <td>{{$teamStWeek->losts}}</td>
                            <td>{{$teamStWeek->goals_difference}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <!--
             Matches
             -->
            <div class="col-3 border-right">
                <label class="font-weight-bold">Match results</label>
                @foreach($matchesByWeek->get($week) as $match)
                    <div class="row mt-3">
                        <div class="col-4">{{$match->home->name}}</div>
                        <div class="col-4">{{$match->home_team_goals}} - {{$match->away_team_goals}}</div>
                        <div class="col-4">{{$match->away->name}}</div>
                    </div>
                @endforeach

            </div>
            <!--
             Forecast
             -->
            <div class="col-3">
                <label class="font-weight-bold">Predictions</label>
                @forelse(($estimationByWeeks->get($week) ?? []) as $estimation)
                    <div class="row">
                        <div class="col-6">{{ $estimation->team->name }}</div>
                        <div class="col-6">{{ round($estimation->wining_percent) }} %</div>
                    </div>
                @empty
                    <div class="row">
                        <div class="col-12 align-content-center">
                            <div class="alert alert-info text-center" role="alert">
                                No estimations yet.
                            </div>

                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    @endforeach
@endsection
