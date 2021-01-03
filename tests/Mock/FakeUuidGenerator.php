<?php

declare(strict_types=1);

namespace App\Tests\Mock;

use App\Shared\Domain\Uuid\UuidGenerator;
use App\Shared\Domain\Uuid\Uuid as UuidInterface;
use App\Shared\Infrastructure\Uuid\Uuid;

final class FakeUuidGenerator implements UuidGenerator
{
    public function __construct(private string $uuid)
    {
    }

    public function generate(): UuidInterface
    {
        return new Uuid($this->uuid);
    }
}
