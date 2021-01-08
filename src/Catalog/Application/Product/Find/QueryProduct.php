<?php

declare(strict_types=1);

namespace App\Catalog\Application\Product\Find;

final class QueryProduct
{
    public function __construct(private string $reference)
    {
    }

    public function getReference(): string
    {
        return $this->reference;
    }
}
