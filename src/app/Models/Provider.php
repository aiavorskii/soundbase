<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Provider extends Model
{
    public $timestamps = false;

    protected $table = 'data_provider';

    protected $fillable = [
        'provider',
        'external_id',
    ];
    public function providable(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'providable_type', 'providable_id');
    }
}
