<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Product;

final class Stock
{
    public function __construct(private int $value)
    {
    }

    public function getValue(): int
    {
        return $this->value;
    }
}
