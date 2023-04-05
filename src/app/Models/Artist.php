<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Provider;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use App\Models\Song;
use App\Models\Album;

class Artist extends Model
{
    public $timestamps = true;

    protected $table = 'artists';

    protected $fillable = [
        'name'
    ];

    public function provider(): MorphOne
    {
        return $this->morphOne(Provider::class, 'providable');
    }

    public function songs()
    {
        return $this->hasMany(Song::class);
    }

    public function albums()
    {
        return $this->belongsToMany(Album::class, 'albums_artists');
    }
}
