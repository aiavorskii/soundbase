<?php

declare(strict_types=1);

namespace App\Http\Repository;

use App\Models\SpotifyToken;
use App\Models\User;
use App\ValueObject\Provider;
use Illuminate\Database\Eloquent\Model;

class UserRepository
{
    // should return one interface
    public function getProviderAuth(User $user, string $provider): ?Model
    {
        // TODO refactor for multiple providers support
        switch($provider)
        {
            case 'spotify':
                return $user->spotify_token;
            case 'soundcloud':
                return null; // TODO not yet implemented
        }
        // temp returning one provider
    }

    public function countUserTracks(User $user, ?Provider $provider = null): int
    {
        // TODO refactor with actual data

        return rand(2, 100000);
    }
}
