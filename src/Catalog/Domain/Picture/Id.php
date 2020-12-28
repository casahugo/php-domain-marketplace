<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Picture;

use App\Shared\Domain\Uuid\UuidInterface;

final class Id
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
