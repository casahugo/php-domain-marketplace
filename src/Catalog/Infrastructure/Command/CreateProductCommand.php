<?php

declare(strict_types=1);

namespace App\Catalog\Infrastructure\Command;

use App\Catalog\Application\CreateProduct\CreateProduct;
use App\Catalog\Domain\Exception\ProductNotFound;

final class CreateProductCommand
{
    private CreateProduct $createProduct;

    public function __construct(
        CreateProduct $createProduct
    ) {
        $this->createProduct = $createProduct;
    }

    public function __invoke(array $payload): int
    {
        try {
            $product = ($this->createProduct)(new \App\Catalog\Application\CreateProduct\CreateProductCommand(
                $payload['reference'],
                $payload['name'],
                $payload['price'],
                $payload['stock'],
                $payload['categoryId']
            ));
        } catch (ProductNotFound $exception) {
            // ...
        }

        return 1;
    }
}