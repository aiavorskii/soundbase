<?php

declare(strict_types=1);

namespace App\Http\Responses;

use stdClass;
use App\Http\Responses\ArtistWrapper;

class TrackWrapper
{
    public function __construct(private stdClass $data)
    {
    }

    public function getSaveData(): array
    {
        return [
            'name' => $this->data->track->name,
            'duration_ms' => $this->data->track->duration_ms,
        ];
    }

    public function getExternalId(): string
    {
        return $this->data->track->id;
    }

    public function getAddedAt(): string
    {
        return $this->data->track->added_at;
    }

    public function getAlbum(): AlbumWrapper
    {
        return new AlbumWrapper($this->data->track->album);
    }

    /**
     * @return ArtistWrapper[]
     */
    public function getArtists(): array
    {
        return array_map(function ($artist) {
            return new ArtistWrapper($artist);
        }, $this->data->track->artists);
    }
}
