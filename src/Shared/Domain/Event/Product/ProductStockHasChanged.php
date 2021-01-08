<?php

declare(strict_types=1);

namespace App\Shared\Domain\Event\Product;

use App\Shared\Domain\Bus\Event\DomainEvent;

final class ProductStockHasChanged implements DomainEvent
{
    public function __construct(private string $productId, private int $stock)
    {
    }

    public function getProductId(): string
    {
        return $this->productId;
    }

    public function getStock(): int
    {
        return $this->stock;
    }
}
