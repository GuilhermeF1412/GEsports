<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\APIController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TeamImageController;
use App\Http\Controllers\TeamController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Game Routes
Route::get('/lolhome', [HomeController::class, 'index'])->name('lolhome');

// Coming Soon Game Pages
Route::view('/valhome', 'pages.valorant')->name('valhome');
Route::view('/cs2home', 'pages.cs2')->name('cs2home');
Route::view('/dota2home', 'pages.dota2')->name('dota2home');
Route::view('/rocketleaguehome', 'pages.rocket-league')->name('rocketleaguehome');

// API Routes
Route::get('/match/{matchId}/{date?}', [APIController::class, 'showMatch'])->name('match.show');

// Team Image Route
Route::get('/store-team-images', [TeamImageController::class, 'storeTeamImage'])->name('store.team.images');

// Team Route
Route::get('/team/{teamName}', [TeamController::class, 'show'])->name('team.show');

// Default Welcome Route
Route::get('/', function () {
    return redirect()->route('lolhome');
});
