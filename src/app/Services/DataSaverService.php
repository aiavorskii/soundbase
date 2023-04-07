<?php

declare(strict_types=1);

namespace App\Services;

use App\Http\Aggregates\AlbumAggregate;
use App\Http\Aggregates\SongAggregate;
use App\Http\Factory\AlbumAggregateFactory;
use App\Http\Factory\ArtistAggregateFactory;
use App\Http\Repository\AlbumRepository;
use App\Http\Repository\ArtistRepository;
use App\Http\Repository\SongRepository;
use App\Http\Responses\AlbumWrapper;
use App\Http\Responses\ArtistWrapper;
use App\Http\Responses\TrackWrapper;
use App\Models\User;
use Database\Factories\AlbumFactory;
use Database\Factories\ArtistFactory;
use Database\Factories\ProviderFactory;
use Database\Factories\SongFactory;

class DataSaverService
{
    public function __construct(
        private SongRepository $songRepository,
        private SongFactory $songFactory,
        private ProviderFactory $providerFactory,
        private AlbumRepository $albumRepository,
        private AlbumFactory $albumFactory,
        private AlbumAggregateFactory $albumAggregateFactory,
        private ArtistRepository $artistRepository,
        private ArtistFactory $artistFactory,
        private ArtistAggregateFactory $artistAggregateFactory
    ) {
    }

    /**
     * Saves song data to the database.
     *
     * @param TrackWrapper $songData
     * @param string $providerName
     */
    public function saveSongData(TrackWrapper $songData, User $user, string $providerName)
    {
        $album = $this->saveAlbumData($songData->getAlbum(), $providerName);
        $artists = $this->saveArtists($songData->getArtists(), $providerName);

        $songAggregate = new SongAggregate(
            $this->songFactory->create($songData->getSaveData()),
            $this->providerFactory->create([
                'provider' => $providerName,
                'external_id' => $songData->getExternalId(),
            ]),
            $album->getRoot(),
            $user,
            $artists
        );

        $this->songRepository->store($songAggregate);
    }

    /**
     * Saves album data to the database.
     *
     * @param AlbumWrapper $albumData
     * @param string $providerName
     *
     * @return AlbumAggregate
     */
    public function saveAlbumData(AlbumWrapper $albumData, string $providerName): AlbumAggregate
    {
        $albumArtists = $this->saveArtists($albumData->getArtists(), $providerName);

        return $this->albumRepository->store(
            $this->albumAggregateFactory->create(
                $this->albumFactory->create($albumData->getSaveData()),
                $this->providerFactory->create([
                    'provider' => $providerName,
                    'external_id' => $albumData->getExternalId(),
                ]),
                $albumArtists
            )
        );
    }

    /**
     * Saves artist data to the database.
     *
     * @param ArtistWrapper $artists
     * @param string $providerName
     *
     * @return int[]
     */
    public function saveArtists(array $artists, string $providerName): array
    {
        $artistIds = [];

        foreach ($artists as $artistData) {
            $artist = $this->artistRepository->store(
                $this->artistAggregateFactory->create(
                    $this->artistFactory->create($artistData->getSaveData()),
                    $this->providerFactory->create([
                        'provider' => $providerName,
                        'external_id' => $artistData->getExternalId(),
                    ]),
                )
            );

            $artistIds[] = $artist->getRoot()->id;
        }

        return $artistIds;
    }
}
