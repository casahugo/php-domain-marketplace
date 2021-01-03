<?php

declare(strict_types=1);

namespace App\Shared\Domain\DataStructure;

abstract class StringValue implements \Stringable
{
    public function __construct(protected string $value)
    {
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
