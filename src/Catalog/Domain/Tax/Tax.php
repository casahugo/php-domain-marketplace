<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Tax;

use App\Shared\Domain\DataStructure\Decimal;

final class Tax
{
    public function __construct(private Code $code, private string $name, private TaxAmount $taxAmount)
    {
    }

    public function getCode(): Code
    {
        return $this->code;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTaxAmount(): TaxAmount
    {
        return $this->taxAmount;
    }

    public function getPercentage(): Decimal
    {
        return $this->taxAmount->divide(100);
    }
}
