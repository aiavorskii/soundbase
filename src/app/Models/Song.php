<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use App\Models\Artist;
use App\Models\Album;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Song extends Model
{
    public $timestamps = true;

    protected $table = 'songs';

    protected $fillable = [
        'name',
        'duration_ms',
    ];

    public function provider(): MorphOne
    {
        return $this->morphOne(Provider::class, 'providable');
    }

    public function artists(): BelongsToMany
    {
        return $this->belongsToMany(Artist::class, 'songs_artist');
    }

    public function album(): BelongsTo
    {
        return $this->belongsTo(Album::class);
    }
}
