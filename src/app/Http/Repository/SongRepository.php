<?php

declare(strict_types=1);

namespace App\Http\Repository;

use App\Http\Aggregates\SongAggregate;
use App\Models\Provider;
use Illuminate\Support\Facades\DB;
use App\Models\Song;
use Psr\SimpleCache\CacheInterface;

class SongRepository
{
    public function __construct(
        private CacheInterface $cache,
    ) {
    }
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
            $song->getRoot()->album()->associate($song->getAlbum());
            $song->getRoot()->user()->attach($song->getUser()->id);
            $song->getRoot()->provider()->save($song->getProvider());
        });

        return $song;
    }

    public function getSongsByProvider(string $provider, int $offset, int $itemsPerPage): array
    {
        $songs = $this->cache->get(
            sprintf('songs:%s:%s:%s', $provider, $offset, $itemsPerPage)
        );

        if (!$songs) {
            $songs = Song::whereHas('provider', function ($query) use ($provider) {
                $query->where('provider', $provider);
            })->with('album', 'artists', 'user')
                ->offset($offset)
                ->limit($itemsPerPage)
                ->get()
                ->toArray();

            $this->cache->set(
                sprintf('songs:%s:%s:%s', $provider, $offset, $itemsPerPage),
                $songs
            );
        }

        return $songs;
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
