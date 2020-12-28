<?php

declare(strict_types=1);

namespace App\Tests\Mock;

use App\Shared\Domain\Uuid\UuidGeneratorInterface;
use App\Shared\Domain\Uuid\UuidInterface;
use App\Shared\Infrastructure\Uuid\Uuid;

final class FakeUuidGenerator implements UuidGeneratorInterface
{
    public function __construct(private string $uuid)
    {
    }

    public function generate(): UuidInterface
    {
        return new Uuid($this->uuid);
    }
}
