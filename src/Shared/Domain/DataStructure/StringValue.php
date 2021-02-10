<?php

declare(strict_types=1);

namespace App\Shared\Domain\DataStructure;

use Stringable;

abstract class StringValue implements Stringable
{
    public function __construct(protected string $value)
    {
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
