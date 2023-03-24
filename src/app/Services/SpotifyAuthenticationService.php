<?php

declare(strict_types=1);

namespace App\Http\Service;

use Illuminate\Http\RedirectResponse;
use SpotifyWebAPI\Session as SpotifySession;

class SpotifyAuthenticationService implements ProviderAuthenticatorInterface
{
    private const PROVIDER_NAME = 'spotify';

    public function __construct(
        private SpotifySession $spotifySesseion
    ) {
    }

    public function getProviderName(): string
    {
        return self::PROVIDER_NAME;
    }

    public function getAccessData(string $code): array
    {
        $this->spotifySesseion->requestAccessToken($code);

        return [
            'access_token' => $this->spotifySesseion->getAccessToken(),
            'refresh_token' => $this->spotifySesseion->getRefreshToken(),
            'expiration' => $this->spotifySesseion->getTokenExpiration(),
        ];
    }

    public function authorize(): RedirectResponse
    {
        // TODO move scopes to config file
        $options = [
            'scope' => [
                'playlist-read-private',
                'user-read-private',
                'user-read-email',
                'playlist-read-collaborative',
                'user-follow-read',
                'user-library-read'
            ]
        ];

        return new RedirectResponse(
            $this->spotifySesseion->getAuthorizeUrl($options)
        );
    }
}
