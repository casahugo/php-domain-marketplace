<?php

declare(strict_types=1);

namespace App\Catalog\Application\CreateProduct;

use App\Catalog\Domain\Category\Category;
use App\Catalog\Domain\Category\Id as CategoryId;
use App\Catalog\Domain\Product\Product;
use App\Catalog\Domain\Product\ProductRepository;
use App\Catalog\Domain\Product\Reference;

final class CreateProduct
{
    private ProductRepository $productRepository;

    public function __construct(
        ProductRepository $productRepository
    ) {
        $this->productRepository = $productRepository;
    }

    public function __invoke(CreateProductCommand $command): Product
    {
        $product = new Product(
            new Reference($command->getReference()),
            $command->getName(),
            $command->getPrice(),
            $command->getStock(),
            new Category(new CategoryId($command->getCategoryId()))
        );

        $this->productRepository->save($product);

        return $product;
    }
}