<?php

declare(strict_types=1);

namespace App\Catalog\Application\Product\Find;

use App\Shared\Domain\Uuid\UuidInterface;

final class QueryProduct
{
    public function __construct(private UuidInterface $reference)
    {
    }

    public function getReference(): UuidInterface
    {
        return $this->reference;
    }
}
