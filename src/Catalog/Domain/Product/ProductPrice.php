<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Product;

use App\Shared\Domain\DataStructure\Decimal;

final class ProductPrice extends Decimal
{
    public function getValue(): float
    {
        return (float) $this->value;
    }

    public function getUnite(): string
    {
        return '€';
    }
}
