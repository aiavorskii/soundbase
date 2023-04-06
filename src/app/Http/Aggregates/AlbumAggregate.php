<?php

declare(strict_types=1);

namespace App\Http\Aggregates;

use App\Models\Album;
use App\Models\Provider;
use App\Models\Artist;

/** @property Artist[] $artists */
class AlbumAggregate
{
    public function __construct(
        private Album $root,
        private Provider $provider,
        private array $artists,
    ) {
    }

    public function getRoot(): Album
    {
        return $this->root;
    }

    public function getProvider(): Provider
    {
        return $this->provider;
    }

    public function getProviderName(): string
    {
        return $this->provider->provider;
    }

    public function getProviderExternalId(): string
    {
        return $this->provider->external_id;
    }

    /**
     * @return Artist[]
     */
    public function getArtists(): array
    {
        return $this->artists;
    }
}
