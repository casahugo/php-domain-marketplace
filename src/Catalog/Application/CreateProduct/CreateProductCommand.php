<?php

declare(strict_types=1);

namespace App\Catalog\Application\CreateProduct;

final class CreateProductCommand
{
    private int $reference;
    private string $name;
    private float $price;
    private int $stock;
    private int $categoryId;

    public function __construct(
        int $reference,
        string $name,
        float $price,
        int $stock,
        int $categoryId
    ) {
        $this->reference = $reference;
        $this->name = $name;
        $this->price = $price;
        $this->stock = $stock;
        $this->categoryId = $categoryId;
    }

    public function getReference(): int
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