<?php

declare(strict_types=1);

namespace App\Catalog\Application\Product\Update;

use App\Shared\Domain\Bus\Command\DomainCommand;
use App\Shared\Domain\Uuid\UuidInterface;

final class UpdateProductCommand implements DomainCommand
{
    public function __construct(
        private UuidInterface $reference,
        private string $name,
        private float $price,
        private int $stock,
        private int $categoryId,
    ) {
    }

    public function getReference(): UuidInterface
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

    public function getCategoryId(): int
    {
        return $this->categoryId;
    }
}
