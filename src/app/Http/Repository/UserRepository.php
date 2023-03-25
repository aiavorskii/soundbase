<?php

declare(strict_types=1);

namespace App\Http\Repository;

use App\Models\SpotifyToken;
use App\Models\User;

class UserRepository
{
    // should return one interface
    public function getProviderAuth(User $user, string $provider): SpotifyToken
    {
        // TODO refactor for multiple providers support
        // temp returning one provider
        return $user->spotify_token;
    }

    public function countUserTracks(User $user, ?string $provider = null): int
    {
        // TODO refactor with actual data
        return rand(2, 100000);
    }
}
