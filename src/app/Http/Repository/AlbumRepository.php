<?php

declare(strict_types=1);

namespace App\Http\Repository;

use App\Models\Album;

class AlbumRepository
{
    public function getOrCreate(array $data, $external_id = null, $provider = null): ?Album
    {
        $internalId = 1; // query to get internal id from albums_provider

        if ($internalId) {
            return Album::find($internalId);
        }

        return Album::create($data);
    }
}

