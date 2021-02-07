<?php

declare(strict_types=1);

namespace App\Shared\Domain\DataStructure;

abstract class IntegerValue
{
    public function __construct(private int $value)
    {
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public static function fromInt(int $value): static
    {
        return new static($value);
    }
}
