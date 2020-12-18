<?php

declare(strict_types=1);

namespace App\Catalog\Infrastructure\Normalizer;

use App\Catalog\Domain\Product\Product;

final class ProductNormalizer
{
    public function normalize(Product $product): array
    {
        return [
            'reference' => $product->getReference()->getValeur(),
            'name' => $product->getName(),
            'price' => $product->getPrice(),
            'category' => $product->getCategory()->getName(),
            'stock' => $product->getStock()
        ];
    }
}