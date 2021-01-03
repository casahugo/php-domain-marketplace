<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Tax;

use App\Shared\Domain\DataStructure\Decimal;

final class Tax
{
    public function __construct(private Code $code, private TaxValue $taxValue)
    {
    }

    public function getCode(): Code
    {
        return $this->code;
    }

    public function getTaxValue(): TaxValue
    {
        return $this->taxValue;
    }

    public function getPercentage(): Decimal
    {
        return $this->taxValue->divide(100);
    }
}
