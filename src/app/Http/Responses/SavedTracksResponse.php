<?php

declare(strict_types=1);

namespace App\Http\Responses;

use stdClass;

class SavedTracksResponse
{
    public function __construct(private stdClass $data)
    {
    }

    /**
     * Get tracks
     *
     * @return stdClass[]
     */
    public function getItems(): array
    {
        return $this->data->items;
    }
}
