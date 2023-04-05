<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\RedirectResponse;
use App\Http\Requests\ProviderAuthCallbackRequest;

// TODO this is basically OAuth2 method, with more providers would need a refactor
interface ProviderAuthenticatorInterface
{
    public function authorize(): RedirectResponse;

    public function getAccessData(ProviderAuthCallbackRequest $request): void; // for now it is array, but may become an interface later

    public function getProviderName(): string;
}
