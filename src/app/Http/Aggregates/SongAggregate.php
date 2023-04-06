<?php

declare(strict_types=1);

namespace App\Http\Aggregates;

use App\Models\Provider;
use App\Models\Song;
use App\Models\Album;
use App\Models\User;

/** @property Artist[] $artists */
class SongAggregate
{
    public function __construct(
        private Song $root,
        private Provider $provider,
        private Album $album,
        private User $user,
        private array $artists,
    ) {
    }

    public function getRoot(): Song
    {
        return $this->root;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getAlbum(): Album
    {
        return $this->album;
    }

    public function getProvider(): Provider
    {
        return $this->provider;
    }

    /**
     * @return Artist[]
     */
    public function getArtists(): array
    {
        return $this->artists;
    }

    public function getProviderName(): string
    {
        return $this->provider->provider;
    }

    public function getProviderExternalId(): string
    {
        return $this->provider->external_id;
    }
}
