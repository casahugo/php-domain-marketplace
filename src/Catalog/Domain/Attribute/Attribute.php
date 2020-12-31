<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Attribute;

use App\Catalog\Domain\Product\Reference;

final class Attribute
{
    public function __construct(
        private Id $id,
        private string $name,
        private int|float|string $value,
        private ?Reference $productReference = null,
    ) {
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getValue(): float|int|string
    {
        return $this->value;
    }

    public function getProductReference(): ?Reference
    {
        return $this->productReference;
    }
}
