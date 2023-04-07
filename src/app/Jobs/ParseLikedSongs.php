<?php

namespace App\Jobs;

use App\Http\Repository\SporifyTokenRepository;
use App\Services\DataSaverService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Http\Responses\SavedTracksResponse;
use Illuminate\Support\Facades\Log;
use SpotifyWebAPI\Session as SpotifySession;
use SpotifyWebAPI\SpotifyWebAPI;

class ParseLikedSongs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private User $user,
        private string $providerName,
        private array $options = [],
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(
        DataSaverService $dataSaverService,
        SpotifyWebAPI $spotifyWebAPI,
        SpotifySession $spotifySession,
        SporifyTokenRepository $tokenRepository

    ): void {
        Log::debug('ParseLikedSongs job started', [
            'user' => $this->user->id,
            'provider' => $this->providerName,
            'options' => $this->options,
        ]);

        $token = $tokenRepository->getUserToken($this->user);
        $spotifyWebAPI->setAccessToken($token->access_token);

        try {
            $response = new SavedTracksResponse(
                $spotifyWebAPI->getMySavedTracks($this->options)
            );
        } catch (\Exception $e) {
            $spotifySession->refreshAccessToken($token->refresh_token);

            $accessToken = $spotifySession->getAccessToken();
            $refreshToken = $spotifySession->getRefreshToken();
            $expiresIn = $spotifySession->getTokenExpiration();

            $tokenRepository->updateToken(
                [
                    'user_id' => $this->user->id,
                ],
                [
                    'access_token' => $accessToken,
                    'refresh_token' => $refreshToken,
                    'expiration' => date('Y-m-d H:i:s', $expiresIn),
                ]
            );

            $spotifyWebAPI->setAccessToken($accessToken);

            $response = new SavedTracksResponse(
                $spotifyWebAPI->getMySavedTracks($this->options)
            );
        }

        foreach ($response->getTracks() as $track) {
            try {
                $dataSaverService->saveSongData($track, $this->user, $this->providerName);
            } catch (\Exception $e) {
                Log::channel('parse')->error($e->getMessage());
                Log::channel('parse')->debug('Track data', [
                    'track' => $track->getSaveData(),
                    'album' => $track->getAlbum()->getSaveData(),
                    'artists' => array_map(function ($artist) {
                        return $artist->getSaveData();
                    }, $track->getArtists()),
                ]);
            }
        }

        if ($response->getOffset() + $response->getLimit() < $response->getTotal()) {
            ParseLikedSongs::dispatch(
                $this->user,
                $this->providerName,
                [
                    'limit' => $response->getLimit(),
                    'offset' => $response->getOffset() + $response->getLimit(),
                ]
            );
        }
    }
}
