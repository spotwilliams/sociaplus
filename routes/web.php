<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [\App\Http\Controllers\LeagueController::class, 'index']);
Route::post('/simulate', [\App\Http\Controllers\LeagueController::class, 'simulate'])->name('simulate');
Route::post('/simulate/all', [\App\Http\Controllers\LeagueController::class, 'simulateAll'])->name('simulate_all');
