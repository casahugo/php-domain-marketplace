<?php

declare(strict_types=1);

namespace App\Shared\Domain\Uuid;

interface UuidGenerator
{
    public function generate(): Uuid;
}
