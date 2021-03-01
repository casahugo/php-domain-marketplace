<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Shipping;

final class Shipping
{
    public function __construct(
        private Code $code,
        private string $name,
        private ShippingPrice $price
    ) {
    }

    public function getCode(): Code
    {
        return $this->code;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): ShippingPrice
    {
        return $this->price;
    }
}
