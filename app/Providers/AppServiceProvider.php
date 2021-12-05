<?php

namespace App\Providers;

use App\Services\CalculateGoalBasedOnStrengths;
use App\Services\MatchResultCalculator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(MatchResultCalculator::class, CalculateGoalBasedOnStrengths::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
