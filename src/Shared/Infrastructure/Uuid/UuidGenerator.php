<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Uuid;

use App\Shared\Domain\Uuid\UuidGeneratorInterface;
use App\Shared\Domain\Uuid\UuidInterface;

final class UuidGenerator implements UuidGeneratorInterface
{
    public function generate(): UuidInterface
    {
        return new Uuid('123-GDTH-142');
    }
}
