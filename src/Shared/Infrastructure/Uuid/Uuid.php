<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Uuid;

use App\Shared\Domain\Uuid\Uuid as UuidInterface;

final class Uuid implements UuidInterface
{
    public function __construct(private string $value)
    {
    }

    public function equal(UuidInterface $uuid): bool
    {
        return $this->value === (string) $uuid;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public static function isValid(string $uuid): bool
    {
        return true;
    }

    public static function fromString(string $uuid): Uuid
    {
        return new self($uuid);
    }
}
