<?php

declare(strict_types=1);

namespace App\Catalog\Infrastructure\Doctrine;

use App\Shared\Domain\Email;
use App\Catalog\Domain\{
    Brand\Brand,
    Brand\Code as BrandCode,
    Category\Category,
    Category\Code as CategoryCode,
    Company\Company,
    Company\Id,
    Exception\ProductDeleteFailedException,
    Exception\ProductNotFound,
    Exception\ProductSaveFailedException,
    Product\Code,
    Product\Product,
    Product\ProductCollection,
    Product\ProductPrice,
    Product\ProductReadModelRepository,
    Product\ProductRepository,
    Product\Reference,
    Product\Status,
    Product\Stock,
    Tax\TaxCollection
};
use DateTimeImmutable;
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
        $product = $this->connection->fetchAssociative(<<<SQL
            SELECT p.*, c.code as category_code, c.name as category_name, b.code as brand_code, b.name as brand_name
            FROM product p
            LEFT JOIN category c ON p.category_id = c.id
            LEFT JOIN brand c ON p.brand_id = c.id
            WHERE p.reference = :reference
        SQL, [':reference' => (string) $reference]);

        if (false === $product) {
            throw new ProductNotFound((string) $reference);
        }

        return new Product(
            Reference::fromString($product['reference']),
            new Code($product['code']),
            $product['name'],
            new ProductPrice((float) $product['price'], 'EUR'),
            new Stock((int) $product['stock']),
            new Brand(new BrandCode($product['brand_code']), $product['brand_name']),
            new Company(Id::fromString('01E439TP9XJZ9RPFH3T1PYBCR8'), new Email('contact@tdl.com'), 'Inc Corporation'),
            new Category(new CategoryCode($product['category_code']), $product['category_name']),
            new TaxCollection(),
            Status::of($product['status']),
            new DateTimeImmutable($product['created_at'])
        );
    }

    public function delete(Product $product): void
    {
        try {
            $this->connection->executeQuery(
                'DELETE FROM product WHERE reference = :reference',
                [':reference' => (string) $product->getReference()]
            );
        } catch (\Throwable $exception) {
            throw new ProductDeleteFailedException((string) $product->getReference(), $exception);
        }
    }

    public function save(Product $product): void
    {
        $save = [
            'reference' => (string) $product->getReference(),
            'code' => (string) $product->getCode(),
            'seller_id' => (string) $product->getCompany()->getId(),
            'category_code' => (string) $product->getCategory()->getCode(),
            'brand_code' => (string) $product->getBrand()->getCode(),
            'name' => $product->getName(),
            'stock' => $product->getStock()->getValue(),
            'price' => $product->getPrice()->getValue(),
            'original_price' => null !== $product->getOriginalPrice() ? $product->getOriginalPrice()->getValue() : null,
            'status' => $product->getStatus()->getValue(),
            'description' => $product->getDescription(),
            'intro' => $product->getIntro(),
            'created_at' => $product->getCreatedAt()->format('Y-m-d'),
            'updated_at' => null !== $product->getUpdatedAt() ? $product->getUpdatedAt()->format('Y-m-d') : null,
        ];

        $result = $this->connection->insert('product', $save);

        if (0 === $result) {
            throw new ProductSaveFailedException((string) $product->getReference());
        }

        $this->readModelRepository->save($product);
    }

    public function list(int $limit, int $offset, array $filters, array $orders = []): ProductCollection
    {
        return new ProductCollection();
    }

    public function findByCode(Code ...$codes): ProductCollection
    {
        return new ProductCollection();
    }
}
