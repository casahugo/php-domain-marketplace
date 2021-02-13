<?php

declare(strict_types=1);

namespace App\Tests\Catalog\Infrastructure\Repository;

use App\Catalog\Domain\Product\Product;
use App\Catalog\Infrastructure\Doctrine\DoctrineProductReadModelRepository;
use App\Catalog\Infrastructure\Doctrine\DoctrineProductRepository;
use App\Tests\Catalog\Factory;
use Doctrine\DBAL\Connection;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\SerializerInterface;

final class DoctrineProductReadModelRepositoryTest extends TestCase
{
    public function testGet(): void
    {
        $repository = new DoctrineProductReadModelRepository(
            $innerService = $this->createMock(DoctrineProductRepository::class),
            $connection = $this->createMock(Connection::class),
            $serializer = $this->createMock(SerializerInterface::class)
        );

        $connection
            ->expects(self::once())
            ->method('fetchOne')
            ->with(
                "SELECT `data` FROM product_readmodel WHERE reference = :reference",
                [
                    'reference' => Factory::PRODUCT_REFERENCE,
                ]
            )
            ->willReturn($product = Factory::getProductJson());

        $serializer
            ->expects(self::once())
            ->method('deserialize')
            ->with($product, Product::class, 'json')
            ->willReturn(Factory::getProduct());

        $repository->get(Factory::getProductReference());
    }

    public function testNotFound(): void
    {
        $repository = new DoctrineProductReadModelRepository(
            $innerService = $this->createMock(DoctrineProductRepository::class),
            $connection = $this->createMock(Connection::class),
            $serializer = $this->createMock(SerializerInterface::class)
        );

        $connection
            ->expects(self::once())
            ->method('fetchOne')
            ->with(
                "SELECT `data` FROM product_readmodel WHERE reference = :reference",
                [
                    'reference' => Factory::PRODUCT_REFERENCE,
                ]
            )
            ->willReturn(false);

        $serializer
            ->expects(self::never())
            ->method('deserialize');

        $innerService
            ->expects(self::once())
            ->method('get')
            ->with(Factory::getProductReference())
            ->willReturn(Factory::getProduct())
        ;

        $repository->get(Factory::getProductReference());
    }

    public function testDelete(): void
    {
        $repository = new DoctrineProductReadModelRepository(
            $innerService = $this->createMock(DoctrineProductRepository::class),
            $connection = $this->createMock(Connection::class),
            $serializer = $this->createMock(SerializerInterface::class)
        );

        $connection
            ->expects(self::once())
            ->method('executeStatement')
            ->with(
                "DELETE FROM product_readmodel WHERE reference = :reference",
                [
                    'reference' => Factory::PRODUCT_REFERENCE,
                ]
            )
            ->willReturn($product = Factory::getProductJson());

        $innerService
            ->expects(self::once())
            ->method('delete')
            ->with(Factory::getProduct())
        ;

        $repository->delete(Factory::getProduct());
    }

    public function testSave(): void
    {
        $repository = new DoctrineProductReadModelRepository(
            $innerService = $this->createMock(DoctrineProductRepository::class),
            $connection = $this->createMock(Connection::class),
            $serializer = $this->createMock(SerializerInterface::class)
        );

        $serializer
            ->expects(self::once())
            ->method('serialize')
            ->with(Factory::getProduct())
            ->willReturn($data = Factory::getProductJson())
        ;

        $innerService
            ->expects(self::once())
            ->method('save')
            ->with(Factory::getProduct())
        ;

        $connection
            ->expects(self::once())
            ->method('executeStatement')
            ->with(
                "INSERT IGNORE INTO product_readmodel(reference, data) VALUE (:reference, :data)",
                [
                    'reference' => Factory::PRODUCT_REFERENCE,
                    'data' => $data,
                ]
            )
            ->willReturn($product = Factory::getProductJson());

        $repository->save(Factory::getProduct());
    }
}
