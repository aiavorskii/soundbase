<?php

use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\UserController;

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
    // redirect to dashboard if authorized
    // redirect to login if not authorized
    return auth()->user() ? $
});

Route::get('login', function () {
    return view('pages.login');
})->name('login');

Route::post('login', [LoginController::class, 'login'])->name('login.login');
Route::post('logout', [LoginController::class, 'logout'])->name('login.logout');

Route::get('user/register', [UserController::class, 'register'])->name('user.register');
Route::post('user/register', [UserController::class, 'create'])->name('user.create');

Route::middleware(['auth'])->group(function () {
    Route::get('user/edit', [UserController::class, 'edit'])->name('user.edit');;
    Route::put('user/update', [UserController::class, 'update'])->name('user.update');
    Route::get('user/providers', [UserController::class, 'providers'])->name('user.providers');

    Route::get('user/{provider}/authorize', [ProviderController::class, 'authorize'])->name('provider.auth');

    Route::get('dashboard', function () {
        return view('pages.dashboard');
    })->name('dashboard');

    Route::get('dashboard/{provider}', function (string $provider) {
        return sprintf('provider page that loads my tracks - %s', $provider);
    })->name('dashboard.provider');

    // these are request to get data for specific tab for each provider
    Route::get('dashboard/{provider}/mytracks', function () {
        return 'tracks';
    });
    Route::get('dashboard/{provider}/myplaylists', function () {
        return 'playlists';
    });

});
