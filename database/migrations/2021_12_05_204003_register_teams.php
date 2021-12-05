<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Team;

class RegisterTeams extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Team::create([
            'name' => 'Arsenal',
            'home_attacking_strength' => 0.87,
            'home_defensive_strength' => 0.91,
            'away_attacking_strength' => 1.15,
            'away_defensive_strength' => 1.45,
        ]);
        Team::create([
            'name' => 'Manchester City',
            'home_attacking_strength' => 1.61,
            'home_defensive_strength' => 0.68,
            'away_attacking_strength' => 1.47,
            'away_defensive_strength' => 0.73,
        ]);
        Team::create([
            'name' => 'Newcastle',
            'home_attacking_strength' => 1.01,
            'home_defensive_strength' => 1.29,
            'away_attacking_strength' => 1.19,
            'away_defensive_strength' => 2.22,
        ]);
        Team::create([
            'name' => 'Liverpool',
            'home_attacking_strength' => 1.11,
            'home_defensive_strength' => 0.91,
            'away_attacking_strength' => 1.42,
            'away_defensive_strength' => 1.64,
        ]);
    }


    public function down()
    {
        Team::whereNotNull('name')->delete();
    }
}
