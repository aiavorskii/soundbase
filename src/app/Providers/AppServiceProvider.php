<?php

namespace App\Providers;

use App\Http\Controllers\ProviderController;
use App\Services\SpotifyAuthenticationService;
use App\ProviderAuthenticatorManager;
use Illuminate\Support\ServiceProvider;
use SpotifyWebAPI\Session as SpotifySession;
use App\Http\Repository\SporifyTokenRepository;
use App\Http\Repository\TokenRepositoryInterface;
use SpotifyWebAPI\SpotifyWebAPI;

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

        $this->app->singleton(SpotifyWebAPI::class, function() {
            $options = [
                'auto_refresh' => true,
            ];

            return new SpotifyWebAPI(
                $options,
                $this->app->make(SpotifySession::class),
            );
        });

        // TODO this is temp need to do this automatically based on provider
        $this->app->singleton(TokenRepositoryInterface::class, SporifyTokenRepository::class);
        // $this->app->when(ProviderController::class)
        //     ->needs(TokenRepositoryInterface::class)
        //     ->give(function(){
        //         return $this->app->make(SporifyTokenRepository::class);
        //     });

        $this->app->singleton(ProviderAuthenticatorManager::class, function() {
            $providerManager = new ProviderAuthenticatorManager();

            $providerManager->addProviderAuthenticator(
                new SpotifyAuthenticationService(
                    $this->app->make(SporifyTokenRepository::class),
                    $this->app->make(SpotifySession::class),
                )
            );

            // add other providers here
            return $providerManager;
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
