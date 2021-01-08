<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Product;

interface ProductProjector
{
    public function push(Product $product): void;

    public function delete(Product $product): void;
}
