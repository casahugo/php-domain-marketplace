<?php

declare(strict_types=1);

namespace App\Tests\Catalog\Infrastructure\Repository;

use App\Catalog\Domain\Company\Id;
use App\Catalog\Domain\Exception\CompanyNotFoundException;
use App\Catalog\Domain\Exception\CompanySaveFailedException;
use App\Catalog\Infrastructure\Doctrine\DoctrineCompanyRepository;
use App\Tests\Catalog\Factory;
use Doctrine\DBAL\Connection;
use PHPUnit\Framework\TestCase;

final class DoctrineCompanyRepositoryTest extends TestCase
{
    public function testGet(): void
    {
        $repository = new DoctrineCompanyRepository(
            $connection = $this->createMock(Connection::class)
        );

        $connection
            ->expects(self::once())
            ->method('fetchAssociative')
            ->with(
                "SELECT id, name, email FROM company WHERE id = :id",
                ['id' => Factory::COMPANY_ID]
            )
            ->willReturn([
                'id' => Factory::COMPANY_ID,
                'email' => Factory::COMPANY_EMAIL,
                'name' => Factory::COMPANY_NAME,
            ]);

        $repository->get(Id::fromString(Factory::COMPANY_ID));
    }

    public function testGetNotFound(): void
    {
        $this->expectException(CompanyNotFoundException::class);

        $repository = new DoctrineCompanyRepository(
            $connection = $this->createMock(Connection::class)
        );

        $connection
            ->expects(self::once())
            ->method('fetchAssociative')
            ->with(
                "SELECT id, name, email FROM company WHERE id = :id",
                ['id' => Factory::COMPANY_ID]
            )
            ->willThrowException(new CompanyNotFoundException(Factory::COMPANY_ID));

        $repository->get(Id::fromString(Factory::COMPANY_ID));
    }

    public function testSave(): void
    {
        $repository = new DoctrineCompanyRepository(
            $connection = $this->createMock(Connection::class)
        );

        $connection
            ->expects(self::once())
            ->method('executeStatement')
            ->with(
                "INSERT IGNORE INTO company(id, email, name) VALUE (:id, :email, :name)",
                [
                    'id' => Factory::COMPANY_ID,
                    'email' => Factory::COMPANY_EMAIL,
                    'name' => Factory::COMPANY_NAME,
                ]
            );

        $repository->save(Factory::getCompany());
    }

    public function testSaveFailed(): void
    {
        $this->expectException(CompanySaveFailedException::class);

        $repository = new DoctrineCompanyRepository(
            $connection = $this->createMock(Connection::class)
        );

        $connection
            ->expects(self::once())
            ->method('executeStatement')
            ->with(
                "INSERT IGNORE INTO company(id, email, name) VALUE (:id, :email, :name)",
                [
                    'id' => Factory::COMPANY_ID,
                    'email' => Factory::COMPANY_EMAIL,
                    'name' => Factory::COMPANY_NAME,
                ]
            )->willReturn(0);

        $repository->save(Factory::getCompany());
    }

    public function testSaveFailedMysql(): void
    {
        $this->expectException(CompanySaveFailedException::class);

        $repository = new DoctrineCompanyRepository(
            $connection = $this->createMock(Connection::class)
        );

        $connection
            ->expects(self::once())
            ->method('executeStatement')
            ->with(
                "INSERT IGNORE INTO company(id, email, name) VALUE (:id, :email, :name)",
                [
                    'id' => Factory::COMPANY_ID,
                    'email' => Factory::COMPANY_EMAIL,
                    'name' => Factory::COMPANY_NAME,
                ]
            )->willThrowException(new \Exception());

        $repository->save(Factory::getCompany());
    }
}
