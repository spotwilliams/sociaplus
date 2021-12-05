<?php

namespace App\Services;

use App\Models\League;
use App\Models\TeamStat;

class CalculateForecastByClosestToPerfectScore implements ForecastCalculator
{
    public function calculateWiningPercents(League $league): array
    {
        // when a team never looses will have the perfect scoring
        $perfectScoring = $league->fixture->weeks * 3;
        // calculate the distance between current score to perfect scoring
        $distances = TeamStat::all()
            ->map(function (TeamStat $stat) use ($perfectScoring) {
                return [
                    'team' => $stat->team,
                    'distance' => $perfectScoring - $stat->points,
                    'score' => $stat->points
                ];
            });

        // sum of all distances represents the 100% of distance to cover
        $sumOfDistances = $distances->reduce(function($carry, $distance) {
            $carry += $distance['distance'];
            return $carry;
        }, 0);

        // represent the distance as percents. The highest percents are the closest distances to perfect score

        $prediction = $distances
            ->map(function (array $distance) use ($sumOfDistances) {
                $distance['wining_percent'] = 100 - ($distance['distance'] / $sumOfDistances * 100);
                return $distance;
            });

        return $prediction->toArray();
    }

}
