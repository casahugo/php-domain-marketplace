<?php

declare(strict_types=1);

namespace App\Shared\Domain\DataStructure;

abstract class StringValue
{
    public function __construct(private string $value)
    {
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
