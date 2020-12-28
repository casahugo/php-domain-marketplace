<?php

declare(strict_types=1);

namespace App\Shared\Domain\Event\Product;

use App\Shared\Domain\{
    Bus\Event\DomainEvent,
    Uuid\UuidInterface
};

final class ProductStockHasChanged implements DomainEvent
{
    public function __construct(private UuidInterface $productId, private int $stock)
    {
    }

    public function getProductId(): UuidInterface
    {
        return $this->productId;
    }

    public function getStock(): int
    {
        return $this->stock;
    }
}
