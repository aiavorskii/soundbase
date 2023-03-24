<?php

use Illuminate\Support\Facades\Route;

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


Route::post('auth', function() {
    return 'perform auth';
})->name('login.auth');


// all routes below should be under auth middleware
Route::get('dashboard', function() {
    return 'redirects to the connected provider, if there are 2 of them use Spotify, if there are no providers redirect to the ';
});

Route::get('dashboard/{provider}', function() {
    return 'provider page that loads my tracks';
});

// these are request to get data for specific tab for each provider
Route::get('dashboard/{provider}/mytracks', function() {
    return 'tracks';
});
Route::get('dashboard/{provider}/myplaylists', function() {
    return 'playlists';
});

Route::get('user/settings', function() {
    return 'here will be page to connect providers and change user info';
});
