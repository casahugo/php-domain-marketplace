<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Product;

use App\Shared\Domain\DataStructure\StringValue;

final class Code extends StringValue
{
    public function equal(Code $code): bool
    {
        return $this->value === $code->value;
    }
}
