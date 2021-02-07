<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Uuid;

use App\Shared\Domain\Uuid\Uuid as UuidInterface;
use App\Shared\Domain\Uuid\UuidGenerator as UuidGeneratorInterface;
use Symfony\Component\Uid\Ulid;

final class UuidGenerator implements UuidGeneratorInterface
{
    public function generate(): UuidInterface
    {
        return new Uuid((string) new Ulid());
    }
}
