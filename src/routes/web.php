<?php

use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProviderController;

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

Route::get('/', function () {
    return view('pages.login');
});

Route::get('/login', function () {
    return view('pages.login');
})->name('login');

Route::post('auth', [LoginController::class, 'login'])->name('login.auth');


// all routes below should be under auth middleware
Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', function () {
        return view('pages.dashboard');
    });

    Route::get('dashboard/{provider}', function () {
        return 'provider page that loads my tracks';
    });

    // these are request to get data for specific tab for each provider
    Route::get('dashboard/{provider}/mytracks', function () {
        return 'tracks';
    });
    Route::get('dashboard/{provider}/myplaylists', function () {
        return 'playlists';
    });

    Route::get('user/settings', function () {
        return 'here will be page to connect providers and change user info';
    });

    Route::get('user/{provider}/authorize', [ProviderController::class, 'authorize']);
});
