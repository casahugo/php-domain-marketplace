<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Shipping;

final class Id
{
    public function __construct(private int $id)
    {
    }

    public function getValue(): int
    {
        return $this->id;
    }
}
