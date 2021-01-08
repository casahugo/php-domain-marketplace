<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Product;

use App\Catalog\Domain\Exception\ProductDeleteFailedException;
use App\Catalog\Domain\Exception\ProductNotFound;
use App\Catalog\Domain\Exception\ProductSaveFailedException;

/**
 * Store Aggregate root.
 */
interface ProductRepository
{
    /** @throws ProductNotFound */
    public function get(Reference $reference): Product;

    /** @throws ProductDeleteFailedException */
    public function delete(Product $product): void;

    /** @throws ProductSaveFailedException */
    public function save(Product $product): void;

    public function list(int $limit, int $offset, array $filters, array $orders = []): ProductCollection;

    public function findByCode(Code ...$codes): ProductCollection;
}
