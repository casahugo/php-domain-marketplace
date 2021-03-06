<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Tax;

use App\Shared\Domain\DataStructure\Decimal;

final class TaxAmount extends Decimal
{
    public function __construct(float|int $value)
    {
        if ($value <= 0) {
            throw new \LogicException("Tax value must be greater than 0");
        }

        parent::__construct($value);
    }
}
