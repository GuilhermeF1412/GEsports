<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\APIController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TeamImageController;

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

// Authentication Routes
Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// Register Routes
Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);

// API Routes
Route::get('/home', [APIController::class, 'todayMatches'])->name('home');
Route::get('/test', [APIController::class, 'index']);
Route::get('/match/{matchId}', [APIController::class, 'showMatch'])->name('match.show');

// Team Image Route
Route::get('/store-team-images', [TeamImageController::class, 'storeTeamImage'])->name('store.team.images');

// User Profile Route
Route::get('/profile', [ProfileController::class, 'index'])->name('profile');

// Default Welcome Route
Route::get('/', function () {
    return view('welcome');
});


Route::get('/home-test', [APIController::class, 'todayMatches'])->name('home-test');
