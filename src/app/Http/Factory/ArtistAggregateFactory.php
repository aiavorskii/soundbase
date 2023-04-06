<?php

declare(strict_types=1);

namespace App\Http\Factory;

use App\Http\Aggregates\ArtistAggregate;
use App\Models\Artist;
use App\Models\Provider;
use Database\Factories\ArtistFactory;
use Database\Factories\ProviderFactory;

class ArtistAggregateFactory
{
    public function __construct(
        private ProviderFactory $providerFactory,
        private ArtistFactory $artistFactory,
    ) {
    }

    public function create(Artist $artist, Provider $provider): ArtistAggregate
    {
        return new ArtistAggregate($artist, $provider);
    }
}
