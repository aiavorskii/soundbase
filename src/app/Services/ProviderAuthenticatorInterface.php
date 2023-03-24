<?php

declare(strict_types=1);

namespace App\Http\Service;

use Illuminate\Http\RedirectResponse;

// TODO this is basically OAuth2 method, with more providers would need a refactor
interface ProviderAuthenticatorInterface
{
    public function authorize(): RedirectResponse;

    public function getAccessData(string $code): array; // for now it is array, but may become an interface later

    public function getProviderName(): string;
}
