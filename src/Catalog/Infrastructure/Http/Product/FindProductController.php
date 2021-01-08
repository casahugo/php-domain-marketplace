<?php

declare(strict_types=1);

namespace App\Catalog\Infrastructure\Http\Product;

use App\Catalog\Application\{
    Product\Find\FindProduct,
    Product\Find\QueryProduct
};
use App\Catalog\Domain\Exception\ProductNotFound;
use App\Catalog\Infrastructure\Normalizer\ProductNormalizer;
use Symfony\Component\HttpFoundation\JsonResponse;

final class FindProductController
{
    public function __construct(
        private FindProduct $finder,
        private ProductNormalizer $productNormalizer
    ) {
    }

    public function __invoke(string $reference): JsonResponse
    {
        try {
            $product = ($this->finder)(new QueryProduct($reference));
        } catch (ProductNotFound $exception) {
            return new JsonResponse(['error' => $exception->getMessage()], JsonResponse::HTTP_NOT_FOUND);
        }

        return new JsonResponse($this->productNormalizer->normalize($product));
    }
}
