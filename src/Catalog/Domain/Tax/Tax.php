<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Tax;

use App\Shared\Domain\DataStructure\Decimal;

final class Tax
{
    public function __construct(private Code $code, private string $name, private TaxAmount $amount)
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

    public function getAmount(): TaxAmount
    {
        return $this->amount;
    }

    public function getPercentage(): Decimal
    {
        return $this->amount->divide(100);
    }
}
