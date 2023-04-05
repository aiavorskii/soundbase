<?php

declare(strict_types=1);

namespace App\Http\Repository;

use App\Models\Artist;

class ArtistRepository
{
    public function getOrCreate(array $data, $external_id = null, $provider = null): ?Artist
    {
        $internalId = 1; // query to get internal id from albums_provider

        if ($internalId) {
            return Artist::find($internalId);
        }

        return Artist::create($data);
    }
}

