<?php

namespace App\Models;

use App\Models\Provider;
use App\Models\Song;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use App\Models\Artist;

class Album extends Model
{
    public $timestamps = true;

    protected $table = 'albums';

    protected $fillable = [
        'name',
        'release_date',
    ];

    public function songs()
    {
        return $this->hasMany(Song::class);
    }

    public function provider(): MorphOne
    {
        return $this->morphOne(Provider::class, 'providable');
    }

    public function artists()
    {
        return $this->belongsToMany(Artist::class, 'albums_artists');
    }
}
