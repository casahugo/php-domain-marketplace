<?php

declare(strict_types=1);

namespace App\Catalog\Application\Product\Find;

use App\Catalog\Domain\Exception\ProductNotFound;
use App\Catalog\Domain\Product\Product;
use App\Catalog\Domain\Product\ProductRepository;
use App\Catalog\Domain\Product\Reference;

final class FindProduct
{
    public function __construct(private ProductRepository $productRepository)
    {
    }

    /** @throws ProductNotFound */
    public function __invoke(QueryProduct $query): Product
    {
        return $this->productRepository->get(Reference::fromString($query->getReference()));
    }
}
