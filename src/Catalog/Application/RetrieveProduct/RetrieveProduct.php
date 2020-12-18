<?php

declare(strict_types=1);

namespace App\Catalog\Application\RetrieveProduct;

use App\Catalog\Domain\Product\Product;
use App\Catalog\Domain\Product\ProductRepository;
use App\Catalog\Domain\Product\Reference;

final class RetrieveProduct
{
    private ProductRepository $productRepository;

    public function __construct(
        ProductRepository $productRepository
    ) {
        $this->productRepository = $productRepository;
    }

    public function __invoke(int $reference): Product
    {
        return $this->productRepository->get(new Reference($reference));
    }
}