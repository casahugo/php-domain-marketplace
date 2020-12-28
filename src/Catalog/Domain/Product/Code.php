<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Product;

final class Code
{
    public function __construct(private string $value)
    {
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
