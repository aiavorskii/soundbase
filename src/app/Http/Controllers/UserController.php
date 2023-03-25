<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Http\Repository\UserRepository;
use App\Models\User;

class UserController extends BaseController
{
    public function __construct(
        private UserRepository $userRepository
    ){
    }

    public function register()
    {
        return view('pages.user.register');
    }

    public function create(Request $request)
    {
        // create user
        // authorize
        // redirect to dashborad
        return view('pages.dashboard');
    }

    public function edit()
    {
        return view('pages.user.edit');
    }

    public function update(Request $request)
    {
        // update user
        // redirect to dashboard
        return view('pages.dashboard');
    }

    public function providers()
    {
        // get all providers, if user is not authorized for provider show
        // provider's login button, if user is authorized for provider, then
        // show to user short infor about provider

        /** @var User $user */
        $user = auth()->user();
        $availableProviders = ['spotify', 'soundcloud'];

        $providers = [];
        foreach ($availableProviders as $provider) {
            $providers[] = [
                'name' => $provider,
                'dashboard' => route('dashboard.provider', ['provider' => $provider]),
                'authorized' => true, // (bool)$this->userRepository->getProviderAuth($user, $provider),
                'tracks_count' => $this->userRepository->countUserTracks($user, $provider),
                'action' => route('provider.auth', [
                    'provider' => $provider
                ]),
            ];
        }

        return view('pages.user.providers', [
            'providers' => $providers,
        ]);
    }
}
