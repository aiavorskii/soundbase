<?php

declare(strict_types=1);

namespace App\Http\Repository;

use App\Models\SpotifyToken;
use App\Models\User;

class SporifyTokenRepository implements TokenRepositoryInterface
{
    public function findOrCreate($searchCriteria, $data): SpotifyToken
    {
        return SpotifyToken::firstOrCreate($searchCriteria, $data);
    }

    public function getUserToken(User $user): ?SpotifyToken
    {
        return $user->spotify_token;
    }
}
