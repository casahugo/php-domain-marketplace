<?php

declare(strict_types=1);

namespace App\Shared\Domain\Event\Product;

use App\Shared\{
    Domain\Bus\Event\DomainEvent,
    Domain\DataStructure\Decimal,
    Domain\Uuid\UuidInterface
};

final class ProductPriceHasChanged implements DomainEvent
{
    public function __construct(
        private UuidInterface $productId,
        private Decimal $price,
        private Decimal $priceWithTax
    ) {
    }

    public function getProductId(): UuidInterface
    {
        return $this->productId;
    }

    public function getProductPrice(): Decimal
    {
        return $this->price;
    }

    public function getProductPriceWithTax(): Decimal
    {
        return $this->priceWithTax;
    }
}
