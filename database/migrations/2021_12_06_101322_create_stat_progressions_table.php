<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatProgressionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stat_progressions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stat_id');
            $table->integer('week')->default(0);
            $table->integer('points')->default(0);
            $table->integer('played_matches')->default(0);
            $table->integer('wins')->default(0);
            $table->integer('draws')->default(0);
            $table->integer('losts')->default(0);
            $table->integer('scored_goals')->default(0);
            $table->integer('conceded_goals')->default(0);
            $table->integer('goals_difference')->default(0);

            $table->foreign('stat_id')->references('id')->on('team_stats');
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
        Schema::dropIfExists('stat_progressions');
    }
}
