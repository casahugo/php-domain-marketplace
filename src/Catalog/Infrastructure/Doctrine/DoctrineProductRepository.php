<?php

declare(strict_types=1);

namespace App\Catalog\Infrastructure\Doctrine;

use App\Catalog\Domain\Exception\ProductDeleteFailedException;
use App\Catalog\Domain\Exception\ProductNotFound;
use App\Catalog\Domain\Exception\ProductSaveFailedException;
use App\Catalog\Domain\Product\Product;
use App\Catalog\Domain\Product\ProductCollection;
use App\Catalog\Domain\Product\ProductRepository;
use App\Catalog\Domain\Product\Reference;

final class DoctrineProductRepository implements ProductRepository
{
    public function get(Reference $reference): Product
    {
        // TODO: Implement get() method.
    }

    public function delete(Product $product): void
    {
        // TODO: Implement delete() method.
    }

    public function save(Product $product): void
    {
        // TODO: Implement save() method.
    }

    public function list(int $limit, int $offset, array $filters, array $orders = []): ProductCollection
    {
        // TODO: Implement list() method.
    }
}
