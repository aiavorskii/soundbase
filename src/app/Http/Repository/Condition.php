<?php

declare(strict_types=1);

namespace App\Http\Repository;

final class Condition
{
    public function __construct(
        public readonly string $column,
        public readonly string $operator,
        public readonly mixed $value,
    ){
    }
}
