<?php

declare(strict_types=1);

namespace App\Tests\Catalog\Infrastructure\Repository;

use App\Catalog\Domain\Exception\CategoryNotFoundException;
use App\Catalog\Domain\Exception\CategorySaveFailedException;
use App\Catalog\Infrastructure\Doctrine\DoctrineCategoryRepository;
use App\Tests\Catalog\Factory;
use Doctrine\DBAL\Connection;
use PHPUnit\Framework\TestCase;

final class DoctrineCategoryRepositoryTest extends TestCase
{
    public function testGet(): void
    {
        $repository = new DoctrineCategoryRepository(
            $connection = $this->createMock(Connection::class)
        );

        $category = Factory::getCategory();

        $connection
            ->expects(self::once())
            ->method('fetchAssociative')
            ->with(
                "SELECT code, name FROM category WHERE code = :code",
                ['code' => 'HRDW']
            )
            ->willReturn(['code' => $category->getCode(), 'name' => $category->getName()]);

        self::assertEquals($category, $repository->get($category->getCode()));
    }

    public function testGetNotFound(): void
    {
        $this->expectException(CategoryNotFoundException::class);

        $repository = new DoctrineCategoryRepository(
            $connection = $this->createMock(Connection::class)
        );

        $connection
            ->expects(self::once())
            ->method('fetchAssociative')
            ->with(
                "SELECT code, name FROM category WHERE code = :code",
                ['code' => 'HRDW']
            )
            ->willReturn(false);

        $repository->get(Factory::getCategoryCode());
    }

    public function testSave(): void
    {
        $repository = new DoctrineCategoryRepository(
            $connection = $this->createMock(Connection::class)
        );

        $category = Factory::getCategory();

        $connection
            ->expects(self::once())
            ->method('executeStatement')
            ->with(
                "INSERT IGNORE INTO category(code, name) VALUE (:code, :name)",
                [
                    'code' => (string) $category->getCode(),
                    'name' => $category->getName(),
                ]
            )
            ->willReturn(1);

        $repository->save($category);
    }

    public function testSaveFailed(): void
    {
        $this->expectException(CategorySaveFailedException::class);

        $repository = new DoctrineCategoryRepository(
            $connection = $this->createMock(Connection::class)
        );

        $category = Factory::getCategory();

        $connection
            ->expects(self::once())
            ->method('executeStatement')
            ->with(
                "INSERT IGNORE INTO category(code, name) VALUE (:code, :name)",
                [
                    'code' => (string) $category->getCode(),
                    'name' => $category->getName(),
                ]
            )
            ->willReturn(0);

        $repository->save($category);
    }

    public function testSaveFailedConnection(): void
    {
        $this->expectException(CategorySaveFailedException::class);

        $repository = new DoctrineCategoryRepository(
            $connection = $this->createMock(Connection::class)
        );

        $category = Factory::getCategory();

        $connection
            ->expects(self::once())
            ->method('executeStatement')
            ->with(
                "INSERT IGNORE INTO category(code, name) VALUE (:code, :name)",
                [
                    'code' => (string) $category->getCode(),
                    'name' => $category->getName(),
                ]
            )
            ->willThrowException(new \Exception());

        $repository->save($category);
    }
}
