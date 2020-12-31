<?php

declare(strict_types=1);

namespace App\Shared\Domain\DataStructure;

abstract class Decimal
{
    public function __construct(protected int|float $value)
    {
    }

    public function getValue(): int|float
    {
        return $this->value;
    }
}
