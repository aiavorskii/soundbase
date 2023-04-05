<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Playlist extends Model
{
    public function provider(): MorphOne
    {
        return $this->morphOne(Provider::class, 'providable');
    }
}
