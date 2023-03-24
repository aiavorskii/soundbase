<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpotifyToken extends Model
{
    use HasFactory;

    protected $table = 'spotify_tokens';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
