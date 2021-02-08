<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Uuid;

use App\Shared\Domain\Uuid\UuidInterface;
use Symfony\Component\Uid\AbstractUid;
use Symfony\Component\Uid\Ulid;

final class Uuid implements UuidInterface
{
    private AbstractUid $value;

    public function __construct(string $value)
    {
        if (false === Ulid::isValid($value)) {
            throw new \InvalidArgumentException("UUID invalid");
        }

        $this->value = Ulid::fromString($value);
    }

    public function getValue(): string
    {
        return (string) $this->value;
    }

    public function equal(UuidInterface $uuid): bool
    {
        return $this->value->equals($uuid);
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }

    public static function isValid(string $uuid): bool
    {
        return Ulid::isValid($uuid);
    }

    public static function fromString(string $uuid): UuidInterface
    {
        return new self($uuid);
    }
}
