<?php

declare(strict_types=1);

namespace App\Shared\Domain\Uuid;

interface Uuid
{
    public function equal(Uuid $uuid): bool;

    public function getValue(): string;

    public function __toString(): string;

    public static function isValid(string $uuid): bool;

    public static function fromString(string $uuid): self;
}
