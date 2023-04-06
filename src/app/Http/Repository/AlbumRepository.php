<?php

declare(strict_types=1);

namespace App\Http\Repository;

use App\Http\Aggregates\AlbumAggregate;
use App\Models\Album;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Repository\Condition;

class AlbumRepository
{
    public function find(int $id)
    {
        /** @var Album $model */
        $model = Album::with(['artists', 'provider'])->findOrFail($id);

        return new AlbumAggregate(
            $model,
            $model->provider,
            $model->artists,
        );
    }

    /**
     * @param Condition[] $conditions
     * @return void
     */
    public function findWithConditions(array $conditions): Collection
    {
        $query = Album::with(['artists', 'provider']);

        foreach($conditions as $condition) {
            $query->where(
                $condition->column,
                $condition->operator,
                $condition->value
            );
        }

        $models = $query->get();

        // convert album aggregate via map callback

        // return new AlbumAggregate(
        //     $model,
        //     $model->provider,
        //     $model->artists,
        // );

        return $models;
    }

    public function store(AlbumAggregate $album): AlbumAggregate
    {
        DB::transaction(function () use ($album) {
            $album->getRoot()
                ->fill([
                    'name' => $album->getRoot()->name,
                    'release_date' => $album->getRoot()->release_date,
                ])->save();

            $album->getRoot()->artists()->attach($album->getArtists());
            $album->getRoot()->provider()->save($album->getProvider());
        });

        return $album;
    }
}
