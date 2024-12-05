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




// API Routes
Route::get('/lolhome', [HomeController::class, 'index'])->name('lolhome');
Route::get('/match/{matchId}/{date?}', [APIController::class, 'showMatch'])->name('match.show');

// Team Image Route
Route::get('/store-team-images', [TeamImageController::class, 'storeTeamImage'])->name('store.team.images');


// Default Welcome Route
Route::get('/', function () {
    return redirect()->route('lolhome');
});

Route::get('/team/{teamName}', [TeamController::class, 'show'])->name('team.show');
