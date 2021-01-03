<?php

declare(strict_types=1);

namespace App\Tests\Mock;

use App\Shared\Domain\Clock\Clock;

final class FrozenClock implements Clock
{
    public function __construct(private \DateTimeImmutable $date)
    {
    }

    public function now(): \DateTimeImmutable
    {
        return $this->date;
    }
}
