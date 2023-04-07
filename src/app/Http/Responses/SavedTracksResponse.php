<?php

declare(strict_types=1);

namespace App\Http\Responses;

use stdClass;
use App\Http\Responses\TrackWrapper;

class SavedTracksResponse
{
    public function __construct(private stdClass $data)
    {
    }

    public function getTracks()
    {
        return array_map(function ($track) {
            return new TrackWrapper($track);
        }, $this->data->items);
    }

    public function getLimit(): int
    {
        return $this->data->limit;
    }

    public function getOffset(): int
    {
        return $this->data->offset;
    }

    public function getTotal(): int
    {
        return $this->data->total;
    }
}
