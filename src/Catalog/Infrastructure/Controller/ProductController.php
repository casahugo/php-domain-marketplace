<?php

declare(strict_types=1);

namespace App\Catalog\Infrastructure\Controller;

use App\Catalog\Application\RetrieveProduct\RetrieveProduct;
use App\Catalog\Domain\Exception\ProductNotFound;
use App\Catalog\Infrastructure\Normalizer\ProductNormalizer;

final class ProductController
{
    private RetrieveProduct $retrieveProduct;
    private ProductNormalizer $productNormalizer;

    public function __construct(
        RetrieveProduct $retrieveProduct,
        ProductNormalizer $productNormalizer
    ) {
        $this->retrieveProduct = $retrieveProduct;
        $this->productNormalizer = $productNormalizer;
    }

    public function __invoke(int $reference): array
    {
        try {
            $product = ($this->retrieveProduct)($reference);
        } catch (ProductNotFound $exception) {
            // ...
        }

        return $this->productNormalizer->normalize($product);
    }
}