<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Aggregates\AlbumAggregate;
use App\Http\Aggregates\SongAggregate;
use App\Http\Repository\AlbumRepository;
use App\Http\Repository\ArtistRepository;
use App\Http\Repository\SongRepository;
use App\Http\Requests\ProviderAuthCallbackRequest;
use App\Models\User;
use App\ProviderAuthenticatorManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller as BaseController;
use SpotifyWebAPI\SpotifyWebAPI;
use App\Http\Repository\TokenRepositoryInterface;
use App\Http\Responses\SavedTracksResponse;
use App\Models\Song;
use App\Http\Factory\AlbumAggregateFactory;
use App\Http\Factory\ArtistAggregateFactory;
use App\Models\Provider;
use Database\Factories\ProviderFactory;
use Database\Factories\AlbumFactory;
use Database\Factories\ArtistFactory;
use Database\Factories\SongFactory;
use stdClass;
use App\Models\Album;

class ProviderController extends BaseController
{
    public function __construct(
        private ProviderAuthenticatorManager $providerAuthenticatorManager,
        private SpotifyWebAPI $spotifyWebAPI,
        private TokenRepositoryInterface $tokenRepository,
        private AlbumRepository $albumRepository,
        private ArtistRepository $artistRepository,
        private SongRepository $songRepository,
        private ArtistAggregateFactory $artistAggregateFactory,
        private AlbumAggregateFactory $albumAggregateFactory,
        private ProviderFactory $providerFactory,
        private AlbumFactory $albumFactory,
        private ArtistFactory $artistFactory,
        private SongFactory $songFactory,
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
        // create reponse wrappers for track, album, artist
        $response = new SavedTracksResponse(
            $this->spotifyWebAPI->getMySavedTracks([
                'offset' => 0,
                'limit'  => 10,
            ])
        );

        foreach ($response->getItems() as $item) {
            $album = $this->saveAlbumData($item->track->album, $providerName);
            $artists = $this->saveArtists($item->track->artists, $providerName);
            $this->saveSong($item->track, $providerName, $album->getRoot(), $user, $artists);
        }
    }

    /**
     * TODO move to service?
     *
     * @param stdClass $songData // TODO create data wrapper for response
     * @param string $providerName
     * @param Album $album
     * @param User $user
     * @param array $artists
     * @return void
     */
    public function saveSong(
        stdClass $songData,
        string $providerName,
        Album $album,
        User $user,
        array $artists
    ) {
        // create with factory
        $songAggregate = new SongAggregate(
            $this->songFactory->create([
                'name' => $songData->track->name,
                'duration_ms' => $songData->track->duration_ms,
            ]),
            $this->providerFactory->create([
                'provider' => $providerName,
                'external_id' => $songData->track->id,
            ]),
            $album->getRoot(),
            $user,
            $artists
        );

        $this->songRepository->store($songAggregate);
    }

    /**
     * TODO move to service?
     *
     * @param stdClass $albumData // TODO create data wrapper for response
     * @param string $providerName
     *
     * @return AlbumAggregate
     */
    function saveAlbumData(stdClass $albumData, string $providerName): AlbumAggregate
    {
        $albumArtists = $this->saveArtists($albumData->artists, $providerName);

        return $this->albumRepository->store(
            $this->albumAggregateFactory->create(
                $this->albumFactory->create([
                    'name' => $albumData->name,
                    'release_date' => $albumData->release_date,
                ]),
                $this->providerFactory->create([
                    'provider' => $providerName,
                    'external_id' => $albumData->id,
                ]),
                $albumArtists
            )
        );
    }

    /**
     * TODO move to service?
     *
     * @param array $artists // TODO create data wrapper for response
     * @param string $providerName
     *
     * @return int[]
     */
    function saveArtists(array $artists, string $providerName): array
    {
        $artistIds = [];

        foreach ($artists as $artistData) {
            $artist = $this->artistRepository->store(
                $this->artistAggregateFactory->create(
                    $this->artistFactory->create([
                        'name' => $artistData->name,
                    ]),
                    $this->providerFactory->create([
                        'provider' => $providerName,
                        'external_id' => $artistData->id,
                    ]),
                )
            );

            $artistIds[] = $artist->getRoot()->id;
        }

        return $artistIds;
    }
}
