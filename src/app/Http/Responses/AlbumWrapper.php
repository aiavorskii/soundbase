<?php

declare(strict_types=1);

namespace App\Http\Responses;

use stdClass;
use App\Http\Responses\ArtistWrapper;

class AlbumWrapper
{
    public function __construct(private stdClass $data)
    {
    }

    public function getSaveData()
    {
        return [
            'name' => $this->data->name,
            'release_date' => $this->data->release_date,
        ];
    }

    public function getExternalId(): string
    {
        return $this->data->id;
    }

    public function getArtists()
    {
        return array_map(function ($artist) {
            return new ArtistWrapper($artist);
        }, $this->data->artists);
    }
}
