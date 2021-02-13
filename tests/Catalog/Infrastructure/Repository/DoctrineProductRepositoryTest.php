<?php

declare(strict_types=1);

namespace App\Tests\Catalog\Infrastructure\Repository;

use App\Catalog\Domain\Exception\ProductNotFound;
use App\Catalog\Infrastructure\Doctrine\DoctrineProductRepository;
use App\Tests\Catalog\Factory;
use Doctrine\DBAL\Connection;
use PHPUnit\Framework\TestCase;

final class DoctrineProductRepositoryTest extends TestCase
{
    public function testGet(): void
    {
        $repository = new DoctrineProductRepository(
            $connection = $this->createMock(Connection::class)
        );

        $connection
            ->expects(self::once())
            ->method('fetchAssociative')
            ->with(
                <<<SQL
            SELECT p.*, c.code as category_code, c.name as category_name, b.code as brand_code, b.name as brand_name,
                   comp.id as company_id, comp.email as company_email, comp.name as company_email
            FROM product p
            LEFT JOIN category c ON p.category_code = c.code
            LEFT JOIN brand b ON p.brand_code = b.code
            LEFT JOIN company comp ON p.company_id = comp.id
            WHERE p.reference = :reference
        SQL, [':reference' => Factory::PRODUCT_REFERENCE]
            )
            ->willReturn(Factory::getProductArray());

        self::assertEquals(Factory::getProduct(), $repository->get(Factory::getProductReference()));
    }

    public function testGetNofFound(): void
    {
        $this->expectException(ProductNotFound::class);

        $repository = new DoctrineProductRepository(
            $connection = $this->createMock(Connection::class)
        );

        $connection
            ->expects(self::once())
            ->method('fetchAssociative')
            ->with(
                <<<SQL
            SELECT p.*, c.code as category_code, c.name as category_name, b.code as brand_code, b.name as brand_name,
                   comp.id as company_id, comp.email as company_email, comp.name as company_email
            FROM product p
            LEFT JOIN category c ON p.category_code = c.code
            LEFT JOIN brand b ON p.brand_code = b.code
            LEFT JOIN company comp ON p.company_id = comp.id
            WHERE p.reference = :reference
        SQL, [':reference' => Factory::PRODUCT_REFERENCE]
            )
            ->willReturn(false);

        $repository->get(Factory::getProductReference());
    }

    public function testSave(): void
    {
        $repository = new DoctrineProductRepository(
            $connection = $this->createMock(Connection::class)
        );

        $product = Factory::getProduct();

        $connection
            ->expects(self::once())
            ->method('insert')
            ->with(
                'product',
                [
                    'reference' => Factory::PRODUCT_REFERENCE,
                    'code' => Factory::PRODUCT_CODE,
                    'company_id' => Factory::COMPANY_ID,
                    'category_code' => Factory::CATEGORY_CODE,
                    'brand_code' => Factory::BRAND_CODE,
                    'name' => Factory::PRODUCT_NAME,
                    'stock' => Factory::PRODUCT_STOCK,
                    'price' => Factory::PRODUCT_PRICE,
                    'original_price' => null,
                    'status' => 'wait_moderation',
                    'description' => null,
                    'intro' => null,
                    'created_at' => $product->getCreatedAt()->format('Y-m-d'),
                    'updated_at' => null !== $product->getUpdatedAt() ? $product->getUpdatedAt()->format('Y-m-d') : null,
                ]
            );

        $repository->save($product);
    }

    public function testDelete(): void
    {
        $repository = new DoctrineProductRepository(
            $connection = $this->createMock(Connection::class)
        );

        $connection
            ->expects(self::once())
            ->method('executeQuery')
            ->with('DELETE FROM product WHERE reference = :reference',
                [':reference' => Factory::PRODUCT_REFERENCE]
            )
            ->willReturn(Factory::getProductArray());

        $repository->delete(Factory::getProduct());
    }
}
