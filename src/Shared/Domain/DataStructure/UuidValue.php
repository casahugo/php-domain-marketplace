<?php

declare(strict_types=1);

namespace App\Shared\Domain\DataStructure;

use App\Shared\Domain\Uuid\UuidInterface;

abstract class UuidValue
{
    public function __construct(private UuidInterface $value)
    {
    }

    public function getValue(): UuidInterface
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }
}
