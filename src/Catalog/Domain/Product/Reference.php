<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Product;

final class Reference
{
    private int $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function getValeur(): int
    {
        return $this->id;
    }
}