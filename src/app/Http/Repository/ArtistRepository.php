<?php

declare(strict_types=1);

namespace App\Http\Repository;

use App\Http\Aggregates\ArtistAggregate;
use Illuminate\Support\Facades\DB;

class ArtistRepository
{
    public function store(ArtistAggregate $artist): ArtistAggregate
    {
        DB::transaction(function () use ($artist) {
            $artist->getRoot()
                ->fill([
                    'name' => $artist->getRoot()->name,
                ])->save();

            $artist->getRoot()->provider()->save($artist->getProvider());
        });

        return $artist;
    }
}
