<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Product;

use App\Catalog\Domain\Category\Category;

final class Product
{
    private Reference $reference;
    private string $name;
    private float $price;
    private int $stock;
    private Category $category;

    public function __construct(
        Reference $reference,
        string $name,
        float $price,
        int $stock,
        Category $category
    ) {
        $this->reference = $reference;
        $this->name = $name;
        $this->price = $price;
        $this->stock = $stock;
        $this->category = $category;
    }

    public function getReference(): Reference
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

    public function getCategory(): Category
    {
        return $this->category;
    }
}