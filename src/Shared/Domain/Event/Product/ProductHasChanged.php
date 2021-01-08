<?php

declare(strict_types=1);

namespace App\Shared\Domain\Event\Product;

use App\Shared\Domain\Bus\Event\DomainEvent;

final class ProductHasChanged implements DomainEvent
{
    public function __construct(private string $productReference)
    {
    }

    public function getProductReference(): string
    {
        return $this->productReference;
    }
}
