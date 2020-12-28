<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Seller;

final class Id
{
    public function __construct(private int $value)
    {
    }

    public function getValue(): int
    {
        return $this->value;
    }
}
