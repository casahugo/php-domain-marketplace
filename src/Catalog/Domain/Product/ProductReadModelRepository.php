<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Product;

interface ProductReadModelRepository
{
    public function get(Reference $reference): Product;

    public function delete(Product $product): void;

    public function save(Product $product): void;
}
