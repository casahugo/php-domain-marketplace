<?php

declare(strict_types=1);

namespace App\Tests\Catalog\Infrastructure\Repository;

use App\Catalog\Domain\Exception\BrandNotFoundException;
use App\Catalog\Domain\Exception\BrandSaveFailedException;
use App\Catalog\Infrastructure\Doctrine\DoctrineBrandRepository;
use App\Tests\Catalog\Factory;
use Doctrine\DBAL\Connection;
use PHPUnit\Framework\TestCase;

final class DoctrineBrandRepositoryTest extends TestCase
{
    public function testGet(): void
    {
        $repository = new DoctrineBrandRepository(
            $connection = $this->createMock(Connection::class)
        );

        $brand = Factory::getBrand();

        $connection
            ->expects(self::once())
            ->method('fetchAssociative')
            ->with(
                "SELECT code, name FROM brand WHERE code = :code",
                ['code' => 'SMSG']
            )
            ->willReturn(['code' => $brand->getCode(), 'name' => $brand->getName()]);

        self::assertEquals($brand, $repository->get($brand->getCode()));
    }

    public function testGetNotFound(): void
    {
        $this->expectException(BrandNotFoundException::class);

        $repository = new DoctrineBrandRepository(
            $connection = $this->createMock(Connection::class)
        );

        $connection
            ->expects(self::once())
            ->method('fetchAssociative')
            ->with(
                "SELECT code, name FROM brand WHERE code = :code",
                ['code' => 'SMSG']
            )
            ->willReturn(false);

        $repository->get(Factory::getBrandCode());
    }

    public function testSave(): void
    {
        $repository = new DoctrineBrandRepository(
            $connection = $this->createMock(Connection::class)
        );

        $brand = Factory::getBrand();

        $connection
            ->expects(self::once())
            ->method('executeStatement')
            ->with(
                "INSERT IGNORE INTO brand(code, name) VALUE (:code, :name)",
                [
                    'code' => (string) $brand->getCode(),
                    'name' => $brand->getName(),
                ]
            )
            ->willReturn(1);

        $repository->save($brand);
    }

    public function testSaveFailed(): void
    {
        $this->expectException(BrandSaveFailedException::class);

        $repository = new DoctrineBrandRepository(
            $connection = $this->createMock(Connection::class)
        );

        $brand = Factory::getBrand();

        $connection
            ->expects(self::once())
            ->method('executeStatement')
            ->with(
                "INSERT IGNORE INTO brand(code, name) VALUE (:code, :name)",
                [
                    'code' => (string) $brand->getCode(),
                    'name' => $brand->getName(),
                ]
            )
            ->willReturn(0);

        $repository->save($brand);
    }

    public function testSaveFailedConnection(): void
    {
        $this->expectException(BrandSaveFailedException::class);

        $repository = new DoctrineBrandRepository(
            $connection = $this->createMock(Connection::class)
        );

        $brand = Factory::getBrand();

        $connection
            ->expects(self::once())
            ->method('executeStatement')
            ->with(
                "INSERT IGNORE INTO brand(code, name) VALUE (:code, :name)",
                [
                    'code' => (string) $brand->getCode(),
                    'name' => $brand->getName(),
                ]
            )
            ->willThrowException(new \Exception());

        $repository->save($brand);
    }
}
