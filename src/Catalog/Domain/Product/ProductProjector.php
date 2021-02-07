<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Product;

interface ProductProjector
{
    public function delete(Product $product): void;

    public function create(Product $product): void;

    public function reset(): void;

    public function configure(): void;
}
