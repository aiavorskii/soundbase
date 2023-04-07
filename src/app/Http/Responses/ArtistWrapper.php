<?php

declare(strict_types=1);

namespace App\Http\Responses;

use stdClass;

class ArtistWrapper
{
    public function __construct(private stdClass $data)
    {
    }

    public function getSaveData()
    {
        return [
            'name' => $this->data->name,
        ];
    }

    public function getExternalId(): string
    {
        return $this->data->id;
    }
}
