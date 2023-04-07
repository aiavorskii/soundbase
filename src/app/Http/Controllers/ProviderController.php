<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Repository\TokenRepositoryInterface;
use App\Http\Requests\ProviderAuthCallbackRequest;
use App\Http\Responses\SavedTracksResponse;
use App\Models\User;
use App\ProviderAuthenticatorManager;
use App\Services\DataSaverService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller as BaseController;
use SpotifyWebAPI\Session as SpotifySession;
use SpotifyWebAPI\SpotifyWebAPI;
use App\Jobs\ParseLikedSongs;

class ProviderController extends BaseController
{
    public function __construct(
        private ProviderAuthenticatorManager $providerAuthenticatorManager,
        private SpotifyWebAPI $spotifyWebAPI,
        private SpotifySession $spotifySession,
        private TokenRepositoryInterface $tokenRepository,
        private DataSaverService $dataSaverService
    ) {
    }

    public function authorize(string $provider): RedirectResponse
    {
        $providerAuthenticator = $this->providerAuthenticatorManager
            ->getProvider($provider);

        return $providerAuthenticator->authorize();
    }

    public function authorizeCallback(ProviderAuthCallbackRequest $request): RedirectResponse
    {
        $providerAuthenticator = $this->providerAuthenticatorManager->getProvider('spotify');
        $providerAuthenticator->getAccessData($request);

        // TODO maybe start job here
        return new RedirectResponse(route('user.providers'));
    }

    public function getData(string $providerName)
    {
        /** @var User $user */
        $user = auth()->user();

        ParseLikedSongs::dispatch(
            $user,
            $providerName,
            [
                'limit' => 50,
                'offset' => 0,
            ]
        );

        return new RedirectResponse(route('dashboard'));
    }
}
