<?php

namespace App\Services;

use App\Models\Fixture;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FixtureCreator
{
    public function schedule(Collection $teams): Fixture
    {
        try {
            DB::beginTransaction();
            /** @var Fixture $fixture */
            $fixture = Fixture::create();
            $collection = collect($teams);
            // Save First element in separated place
            $fixedTeam = $collection->first();

            // remove first item
            $collection->shift(1);

            $numberOfMatches = $collection->count() * 2;

            $matches = 0;

            while ($matches < $numberOfMatches) {
                // let's rotate teams
                $rotateThisTeam = $collection->first();
                // remove the rotated team
                $collection->shift(1);
                // add the rotated team into at last position
                $collection->push($rotateThisTeam);
                // Re-insert first team and now split collection into pairs
                $matchesOfCurrentWeek = collect([$fixedTeam])->merge($collection)->chunk(2);

                $fixture->addMatchesOfWeek($matchesOfCurrentWeek);

                $matches++;
            }

            $fixture->save();

            DB::commit();
            return $fixture;
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            throw new \Exception('Something went wrong');
        }
    }
}
