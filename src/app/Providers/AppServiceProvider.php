<?php

namespace App\Providers;

use App\Http\Service\SpotifyAuthenticationService;
use App\ProviderAuthenticatorManager;
use Illuminate\Support\ServiceProvider;
use SpotifyWebAPI\Session as SpotifySession;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(SpotifySession::class, function() {
            return new SpotifySession(
                env('SPOTIFY_CLIENT_ID'),
                env('SPOTIFY_CLIENT_SECRET'),
                env('REDIRECT_URI')
            );
        });


        $this->app->singleton(ProviderAuthenticatorManager::class, function() {
            $providerManager = new ProviderAuthenticatorManager();

            $providerManager->addProviderAuthenticator(
                new SpotifyAuthenticationService(
                    $this->app->make(SpotifySession::class)
                )
            );
            
            // add other providers here
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
