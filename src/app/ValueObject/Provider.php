<?php

declare(strict_types=1);

namespace App\ValueObject;

use \Stringable;

class Provider implements Stringable
{
    const PROVIDER_SPOTIFY    = 'spotify';
    const PROVIDER_SOUNDCLOUD = 'soundcloud';

    const SUPPORTED_PROVIDERS = [
        self::PROVIDER_SPOTIFY,
        self::PROVIDER_SOUNDCLOUD,
    ];

    private string $value;

    public function __construct(string $value)
    {
        if (!in_array($value, self::SUPPORTED_PROVIDERS)) {
            // throw exception
        }

        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
