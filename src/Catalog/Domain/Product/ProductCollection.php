<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Product;

use App\Shared\Domain\DataStructure\Collection;

final class ProductCollection extends Collection
{
    public function __construct()
    {
        parent::__construct(Product::class);
    }

    public function findByCode(Code $code): ?Product
    {
        return $this->findFirst(fn(Product $product): bool => $product->getCode()->equal($code));
    }
}
