<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Tax;

use App\Shared\Domain\DataStructure\Decimal;

final class Tax
{
    public function __construct(private Code $code, private Decimal $taxValue)
    {
    }

    public function getCode(): Code
    {
        return $this->code;
    }

    public function getTaxValue(): Decimal
    {
        return $this->taxValue;
    }
}
