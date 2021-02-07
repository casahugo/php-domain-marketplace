<?php

declare(strict_types=1);

namespace App\Catalog\Infrastructure\Doctrine;

use App\Catalog\Domain\{
    Exception\ProductDeleteFailedException,
    Exception\ProductNotFound,
    Exception\ProductSaveFailedException,
    Product\Product,
    Product\ProductCollection,
    Product\ProductRepository,
    Product\Reference
};
use Doctrine\DBAL\Connection;
use Symfony\Component\Serializer\SerializerInterface;

final class DoctrineProductRepository implements ProductRepository
{
    public function __construct(
        private Connection $connection,
        private SerializerInterface $serializer
    ) {
    }

    public function get(Reference $reference): Product
    {
        $product = $this->connection->fetchOne(
            "SELECT `data` FROM product_readmodel WHERE id = :id",
            [
                'id' => $reference,
            ]
        );

        if (false === $product) {
            throw new ProductNotFound((string) $reference);
        }

        $this->serializer->deserialize($product, Product::class, 'json');
    }

    public function delete(Product $product): void
    {
        $result = $this->connection->executeStatement(
            "DELETE FROM product_readmodel WHERE id = :id",
            [
                'id' => $product->getReference(),
            ]
        );

        if ($result === 0) {
            throw new ProductDeleteFailedException((string) $product->getReference());
        }
    }

    public function save(Product $product): void
    {
        $result = $this->connection->executeStatement(
            "INSERT IGNORE INTO product_readmodel(reference, data) VALUE (:reference, :data)",
            [
                'reference' => (string) $product->getReference(),
                'data' => $this->serializer->serialize($product, 'json'),
            ]
        );

        if ($result === 0) {
            throw new ProductSaveFailedException((string) $product->getReference());
        }
    }

    public function list(int $limit, int $offset, array $filters, array $orders = []): ProductCollection
    {
        return new ProductCollection();
    }
}
