<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\SpotifyToken;
use App\ProviderAuthenticatorManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class ProviderController extends BaseController
{
    public function __construct(
        private ProviderAuthenticatorManager $providerAuthenticatorManager,
    ) {
    }

    public function authorize(string $provider): RedirectResponse
    {
        $providerAuthenticator = $this->providerAuthenticatorManager
            ->getProvider($provider);

        return $providerAuthenticator->authorize();
    }

    public function authorizeCallback(Request $request)
    {
        // hardcoded case for Spotify
        $code = $request->get('code');

        $providerAuthenticator = $this->providerAuthenticatorManager->getProvider('spotify');
        $providerAuthenticator->getAccessData($code);

        // TODO create a repository manager and repository for each provider??
        // TODO first or create by user_id
        SpotifyToken::create(
            ['code' =>$code] + $providerAuthenticator->getAccessData($code)
        );
    }
}
