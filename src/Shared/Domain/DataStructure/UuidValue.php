<?php

declare(strict_types=1);

namespace App\Shared\Domain\DataStructure;

use App\Shared\Domain\Uuid\Uuid as UuidInterface;
use App\Shared\Infrastructure\Uuid\Uuid;
use Stringable;

abstract class UuidValue implements Stringable
{
    final public function __construct(private UuidInterface $value)
    {
    }

    public static function fromString(string $value): static
    {
        return new static(Uuid::fromString($value));
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
