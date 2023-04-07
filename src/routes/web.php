<?php

use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\UserController;
use App\ValueObject\Provider;
use App\Http\Controllers\DashboardController;
use App\Models\Artist;

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
    return auth()->user() ? redirect('dashboard') : redirect('login');
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
    Route::get('user/{provider}/callback', [ProviderController::class, 'authorizeCallback'])->name('provider.callback');
    Route::get('user/{provider}/get-data', [ProviderController::class, 'getData'])->name('provider.data');

    Route::get('dashboard', function () {
        return redirect(route('dashboard.mytracks', ['provider' => Provider::PROVIDER_SPOTIFY]));
    })->name('dashboard');

    Route::get('dashboard/{provider}', function (Provider $provider) {
        return redirect(route('dashboard.mytracks', ['provider' => Provider::PROVIDER_SPOTIFY]));
    })->name('dashboard.provider');

    // these are request to get data for specific tab for each provider
    Route::get('dashboard/{provider}/mytracks', [DashboardController::class, 'mytracks'])
        ->name('dashboard.mytracks')
        ->where('provider', implode('|', Provider::SUPPORTED_PROVIDERS));

    Route::get('dashboard/{provider}/myplaylists', function () {
        return 'playlists';
    });

});


Route::get('artist/{artist}', function (Artist $artist) {
    return sprintf('artist page - %s', $artist->name);
})->name('artist');

Route::get('test', [DashboardController::class, 'test']);
