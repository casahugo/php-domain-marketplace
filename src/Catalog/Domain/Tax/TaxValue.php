<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Tax;

use App\Shared\Domain\DataStructure\Decimal;

final class TaxValue extends Decimal
{
    public function __construct(float|int $value)
    {
        if ($value === 0 || $value === 0.) {
            throw new \LogicException("Tax value must be greater thean 0");
        }

        parent::__construct($value);
    }
}
