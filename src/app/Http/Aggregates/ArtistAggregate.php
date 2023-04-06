<?php

declare(strict_types=1);

namespace App\Http\Aggregates;

use App\Models\Artist;
use App\Models\Provider;

class ArtistAggregate
{
    public function __construct(
        private Artist $root,
        private Provider $provider,
    ) {
    }

    public function getRoot(): Artist
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
}
