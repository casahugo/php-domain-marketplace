<?php

declare(strict_types=1);

namespace App\Shared\Domain\Event\Product;

use App\Shared\Domain\Bus\Event\DomainEvent;

final class ProductPriceHasChanged implements DomainEvent
{
    public function __construct(
        private string $productId,
        private float $price,
        private ?float $priceWithTax
    ) {
    }

    public function getProductId(): string
    {
        return $this->productId;
    }

    public function getProductPrice(): float
    {
        return $this->price;
    }

    public function getProductPriceWithTax(): ?float
    {
        return $this->priceWithTax;
    }
}
