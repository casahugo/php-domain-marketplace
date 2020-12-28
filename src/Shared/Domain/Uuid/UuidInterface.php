<?php

declare(strict_types=1);

namespace App\Shared\Domain\Uuid;

interface UuidInterface
{
    public function equal(UuidInterface $uuid): bool;

    public function __toString(): string;

    public static function isValid(string $uuid): bool;

    public static function fromString(string $uuid): self;
}
