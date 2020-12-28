<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Document;

use App\Shared\Domain\Uuid\UuidInterface;

final class Id
{
    public function __construct(private UuidInterface $id)
    {
    }
}
