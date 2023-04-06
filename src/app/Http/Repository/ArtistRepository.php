<?php

declare(strict_types=1);

namespace App\Http\Repository;

use App\Http\Aggregates\ArtistAggregate;

class ArtistRepository
{
    public function store(ArtistAggregate $artistAggregate): ArtistAggregate
    {
        return $artistAggregate;
    }
}
