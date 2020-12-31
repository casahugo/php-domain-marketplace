<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Product;

use App\Catalog\Domain\Tax\Tax;
use App\Shared\Domain\DataStructure\Decimal;

final class ProductPrice extends Decimal
{
    public function __construct(float $value, private string $currency = 'EUR')
    {
        parent::__construct($value);
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function addTax(Tax $tax): self
    {
        $decimal = $this->addition($this->multiply($tax->getPercentage()));

        return new self($decimal->value);
    }
}
