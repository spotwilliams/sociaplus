<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeamStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('team_stats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('team_id');
            $table->unsignedBigInteger('fixture_id');
            $table->integer('points')->default(0);
            $table->integer('played_matches')->default(0);
            $table->integer('wins')->default(0);
            $table->integer('draws')->default(0);
            $table->integer('losts')->default(0);
            $table->integer('scored_goals')->default(0);
            $table->integer('conceded_goals')->default(0);
            $table->integer('goals_difference')->default(0);

            $table->foreign('team_id')->references('id')->on('teams');
            $table->foreign('fixture_id')->references('id')->on('fixtures');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('team_stats');
    }
}
