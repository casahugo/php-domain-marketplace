<?php

declare(strict_types=1);

namespace App\Catalog\Infrastructure\Doctrine;

use App\Catalog\Domain\Exception\ProductDeleteFailedException;
use App\Catalog\Domain\Exception\ProductSaveFailedException;
use App\Catalog\Domain\Product\Code;
use App\Catalog\Domain\Product\Product;
use App\Catalog\Domain\Product\ProductCollection;
use App\Catalog\Domain\Product\ProductRepository;
use App\Catalog\Domain\Product\Reference;
use Doctrine\DBAL\Connection;
use Symfony\Component\Serializer\SerializerInterface;

final class DoctrineProductReadModelRepository implements ProductRepository
{
    public function __construct(
        private DoctrineProductRepository $inner,
        private Connection $connection,
        private SerializerInterface $serializer,
    ) {
    }

    public function get(Reference $reference): Product
    {
        $product = $this->connection->fetchOne(
            "SELECT `data` FROM product_readmodel WHERE reference = :reference",
            [
                'reference' => (string) $reference,
            ]
        );

        if (false === $product) {
            return $this->inner->get($reference);
        }

        return $this->serializer->deserialize($product, Product::class, 'json');
    }

    public function delete(Product $product): void
    {
        $this->inner->delete($product);

        $result = $this->connection->executeStatement(
            "DELETE FROM product_readmodel WHERE reference = :reference",
            [
                'reference' => $product->getReference(),
            ]
        );

        if ($result === 0) {
            throw new ProductDeleteFailedException((string) $product->getReference());
        }
    }

    public function save(Product $product): void
    {
        $this->inner->save($product);

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
        return $this->inner->list($limit, $offset, $filters, $orders);
    }

    public function findByCode(Code ...$codes): ProductCollection
    {
        return $this->inner->findByCode(...$codes);
    }
}
