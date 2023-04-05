<?php

declare(strict_types=1);

namespace App\Http\Repository;

use App\Models\User;
use App\Models\SpotifyToken;

interface TokenRepositoryInterface
{
    public function findOrCreate($searchCriteria, $data): SpotifyToken;

    public function getUserToken(User $user): ?SpotifyToken;
}
