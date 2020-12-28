<?php

declare(strict_types=1);

namespace App\Shared\Domain\Clock;

interface ClockInterface
{
    public function now(): \DateTimeImmutable;
}
