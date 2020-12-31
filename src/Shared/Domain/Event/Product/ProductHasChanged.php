<?php

declare(strict_types=1);

namespace App\Shared\Domain\Event\Product;

use App\Shared\Domain\Bus\Event\DomainEvent;
use App\Shared\Domain\Uuid\UuidInterface;

final class ProductHasChanged implements DomainEvent
{
    public function __construct(private UuidInterface $productReference)
    {
    }

    public function getProductReference(): UuidInterface
    {
        return $this->productReference;
    }
}
