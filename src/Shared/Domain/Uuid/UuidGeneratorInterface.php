<?php

declare(strict_types=1);

namespace App\Shared\Domain\Uuid;

interface UuidGeneratorInterface
{
    public function generate(): UuidInterface;
}
