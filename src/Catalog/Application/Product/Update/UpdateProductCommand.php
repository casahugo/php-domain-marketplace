<?php

declare(strict_types=1);

namespace App\Catalog\Application\Product\Update;

use App\Shared\Domain\Bus\Command\DomainCommand;

final class UpdateProductCommand implements DomainCommand
{
    public function __construct(
        private string $reference,
        private string $name,
        private float $price,
        private int $stock,
        private string $categoryCode,
    ) {
    }

    public function getReference(): string
    {
        return $this->reference;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getStock(): int
    {
        return $this->stock;
    }

    public function getCategoryCode(): string
    {
        return $this->categoryCode;
    }
}
