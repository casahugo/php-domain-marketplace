<?php

declare(strict_types=1);

namespace App\Catalog\Infrastructure\Doctrine;

use App\Catalog\Domain\{
    Product\Product,
    Product\ProductCollection,
    Product\ProductReadModelRepository,
    Product\ProductRepository,
    Product\Reference};
use Doctrine\DBAL\Connection;

final class DoctrineProductRepository implements ProductRepository
{
    public function __construct(
        private Connection $connection,
        private ProductReadModelRepository $readModelRepository
    ) {
    }

    public function get(Reference $reference): Product
    {

    }

    public function delete(Product $product): void
    {

    }

    public function save(Product $product): void
    {
        // save relationnal
        // ...

        $this->readModelRepository->save($product);
    }

    public function list(int $limit, int $offset, array $filters, array $orders = []): ProductCollection
    {
        return new ProductCollection();
    }
}
