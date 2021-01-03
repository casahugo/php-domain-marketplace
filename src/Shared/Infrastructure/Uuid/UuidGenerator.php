<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Uuid;

use App\Shared\Domain\Uuid\UuidGenerator as UuidGeneratorInterface;

final class UuidGenerator implements UuidGeneratorInterface
{
    public function generate(): Uuid
    {
        return new Uuid('123-GDTH-142');
    }
}
