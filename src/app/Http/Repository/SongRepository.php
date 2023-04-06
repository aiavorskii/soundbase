<?php

declare(strict_types=1);

namespace App\Http\Repository;

use App\Http\Aggregates\SongAggregate;
use App\Models\Provider;
use Illuminate\Support\Facades\DB;
use App\Models\Song;

class SongRepository
{
    public function store(SongAggregate $song)
    {
        // check if it is already exist in song
        DB::transaction(function () use ($song) {
            $song->getRoot()
                ->fill([
                    'name' => $song->getRoot()->name,
                    'duration_ms' => $song->getRoot()->duration_ms,
                ])->save();

            $song->getRoot()->artists()->attach($song->getArtists());
            $song->getRoot()->user()->attach($song->getUser()->id);
            $song->getRoot()->song()->associate($song->getAlbum());
        });

        return $song;
    }

    public function findByNameAndProvider(string $name, Provider $provider): ?Song
    {
        return Song::where('name', $name)
            ->whereHas('providers', function ($query) use ($provider) {
                $query->where('external_id', $provider->external_id)
                    ->where('provider', $provider->provider);
            })->first();
    }
}
