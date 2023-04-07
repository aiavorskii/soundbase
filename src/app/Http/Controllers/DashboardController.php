<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Repository\SongRepository;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;

class DashboardController extends BaseController
{
    const ITEMS_PER_PAGE = 10;

    public function __construct(
        private SongRepository $songRepository,
    ) {

    }
    public function mytracks(string $provider, Request $request)
    {
        $page = $request->get('page', 1);
        $offset = ($page - 1) * self::ITEMS_PER_PAGE;

        return view('pages.dashboard', [
            'provider' => $provider,
            'likedTracks' => $this->songRepository
                ->getSongsByProvider($provider, $offset, self::ITEMS_PER_PAGE),
        ]);
    }
}
