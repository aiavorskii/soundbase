<?php

declare(strict_types=1);

namespace App;

use App\Services\ProviderAuthenticatorInterface;

/**
 * @property ProviderAuthenticatorInterface[] $providerAuthenticators
 */
class ProviderAuthenticatorManager
{
    private array $providerAuthenticators;

    public function addProviderAuthenticator(
        ProviderAuthenticatorInterface $providerAuthenticator
    ): void {
        $this->providerAuthenticators[$providerAuthenticator->getProviderName()] = $providerAuthenticator;
    }

    public function getProvider(string $name): ProviderAuthenticatorInterface
    {
        return $this->providerAuthenticators[$name];
    }
}
