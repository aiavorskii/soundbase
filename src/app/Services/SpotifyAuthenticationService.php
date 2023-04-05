<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\RedirectResponse;
use SpotifyWebAPI\Session as SpotifySession;
use App\Http\Requests\ProviderAuthCallbackRequest;
use App\Http\Repository\TokenRepositoryInterface;
use App\Http\Repository\SporifyTokenRepository;

/**
 * @property SporifyTokenRepository $tokenRepository
 */
class SpotifyAuthenticationService implements ProviderAuthenticatorInterface
{
    private const PROVIDER_NAME = 'spotify';

    public function __construct(
        private TokenRepositoryInterface $tokenRepository,
        private SpotifySession $spotifySesseion,
    ) {
    }

    public function getProviderName(): string
    {
        return self::PROVIDER_NAME;
    }

    public function getAccessData(ProviderAuthCallbackRequest $request): void
    {
        $code = $request->get('code');
        $this->spotifySesseion->requestAccessToken($code);

        $this->tokenRepository->findOrCreate(
        [
            'user_id' => auth()->user()->id,
        ],
        [
            'code' => $code,
            'access_token' => $this->spotifySesseion->getAccessToken(),
            'refresh_token' => $this->spotifySesseion->getRefreshToken(),
            'expiration' => date('Y-m-d H:i:s', $this->spotifySesseion->getTokenExpiration()),
        ]);
    }

    public function authorize(): RedirectResponse
    {
        // TODO move scopes to config file
        $options = [
            // 'scope' => config(sprintf('providers.%s.scope', self::PROVIDER_NAME)),
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
