<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Product;

interface ProductProjector
{
    public function __invoke(Product $product): void;
}
