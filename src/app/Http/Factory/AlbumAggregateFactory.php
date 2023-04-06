<?php

declare(strict_types=1);

namespace App\Http\Factory;

use App\Http\Aggregates\AlbumAggregate;
use App\Models\Album;
use App\Models\Provider;

class AlbumAggregateFactory
{
    public function __construct(
    ) {
    }

    public function create(
        Album $album,
        Provider $provider,
        array $artists
    ) {
        return new AlbumAggregate($album, $provider, $artists);
    }
}
