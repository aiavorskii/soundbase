<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ProviderAuthCallbackRequest;
use App\Models\User;
use App\ProviderAuthenticatorManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller as BaseController;
use SpotifyWebAPI\SpotifyWebAPI;
use App\Http\Repository\TokenRepositoryInterface;
use App\Http\Responses\SavedTracksResponse;
use App\Models\Artist;
use App\Models\Provider;
use Exception;
use App\Models\Album;
use App\Models\Song;

class ProviderController extends BaseController
{
    public function __construct(
        private ProviderAuthenticatorManager $providerAuthenticatorManager,
        private SpotifyWebAPI $spotifyWebAPI,
        private TokenRepositoryInterface $tokenRepository
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

        $token = $this->tokenRepository->getUserToken($user);
        $this->spotifyWebAPI->setAccessToken($token->access_token);

        // put everything under one class
        $response = new SavedTracksResponse(
            $this->spotifyWebAPI->getMySavedTracks([
                'offset' => 0,
                'limit'  => 10,
            ])
        );

        foreach ($response->getItems() as $item) {
            // saving albums
            // TODO advanced condition check with joins before creating
            $album = Album::firstOrCreate([
                'name' => $item->track->album->name,
                'release_date' => $item->track->album->release_date,
            ]);

            $album->provider()->firstOrCreate([
                'provider' => $providerName,
                'external_id' => $item->track->album->id,
            ]);

            $albumArtists = [];
            foreach ($item->track->album->artists as $artistData) {
                try {
                    // TODO advanced condition check with joins before creating
                    $artist = Artist::firstOrCreate([
                        'name' => $artistData->name
                    ]);

                    $artist->provider()->firstOrCreate([
                        'provider' => $providerName,
                        'external_id' => $artistData->id,
                    ]);

                    $albumArtists[] = $artist->id;
                } catch (Exception $e) {
                }
            }

            $album->artists()->attach($albumArtists);
            // end of saving albums // TODO put this in albums repo

            // saving artists
            $artists = [];
            foreach ($item->track->artists as $artistData) {
                try {
                    // TODO advanced condition check with joins before creating
                    $artist = Artist::firstOrCreate([
                        'name' => $artistData->name
                    ]);

                    $artist->provider()->firstOrCreate([
                        'provider' => $providerName,
                        'external_id' => $artistData->id,
                    ]);

                    $artists[] = $artist->id;
                } catch (Exception $e) {
                }
            }
            // end of saving albums // TODO put this in artists repo

            // saving song
            // TODO advanced condition check with joins before creating
            $song = Song::firstOrCreate([
                'name' => $item->track->name,
                'duration_ms' => $item->track->duration_ms,
            ]);

            $song->artists()->attach($artists);
            $song->album()->associate($album->id);
            $song->provider()->firstOrCreate([
                'provider' => $providerName,
                'external_id' => $item->track->id,
            ]);

            // attaching artists to a song
            $data = [];
            // **save track functionality
            // saving artists
            // saving albums
            // saving song
        }
    }
}
