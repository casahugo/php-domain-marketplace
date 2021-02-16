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
    Product\ProductRepository,
    Product\Reference,
    Product\Status,
    Product\Stock,
    Shipping\Code as ShippingCode,
    Shipping\Shipping,
    Shipping\ShippingPrice,
    Tax\TaxCollection};
use DateTimeImmutable;
use Doctrine\DBAL\Connection;

class DoctrineProductRepository implements ProductRepository
{
    public function __construct(
        private Connection $connection,
    ) {
    }

    public function get(Reference $reference): Product
    {
        $product = $this->connection->fetchAssociative(<<<SQL
            SELECT p.*, c.code as category_code, c.name as category_name, b.code as brand_code, b.name as brand_name,
                   comp.id as company_id, comp.email as company_email, comp.name as company_email,
                   sh.code as shipping_code, sh.name as shipping_name, sh.price as shipping_price
            FROM product p
            LEFT JOIN category c ON p.category_code = c.code
            LEFT JOIN brand b ON p.brand_code = b.code
            LEFT JOIN company comp ON p.company_id = comp.id
            INNER JOIN shipping sh ON p.shipping_code = sh.code
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
            new Company(Id::fromString($product['company_id']), new Email($product['company_email']), $product['company_name']),
            new Category(new CategoryCode($product['category_code']), $product['category_name']),
            new TaxCollection(),
            Status::of($product['status']),
            new DateTimeImmutable($product['created_at']),
            isset($product['updated_at']) ? new DateTimeImmutable($product['updated_at']) : null,
            isset($product['shipping_code'])
                ? new Shipping(new ShippingCode($product['shipping_code']), $product['shipping_name'], new ShippingPrice($product['shipping_price']))
                : null
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
            'company_id' => (string) $product->getCompany()->getId(),
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
            'shipping_code' => null !== $product->getShipping() ? (string) $product->getShipping()->getCode() : null,
        ];

        $result = $this->connection->insert('product', $save);

        if (0 === $result) {
            throw new ProductSaveFailedException((string) $product->getReference());
        }
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
