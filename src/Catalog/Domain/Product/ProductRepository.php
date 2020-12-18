<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Product;

use App\Catalog\Domain\Exception\ProductNotFound;

interface ProductRepository
{
    /** @throws ProductNotFound */
    public function get(Reference $reference): Product;

    public function delete(Product $product): void;

    public function save(Product $product): void;
}