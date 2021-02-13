<?php

declare(strict_types=1);

namespace App\Catalog\Infrastructure\Doctrine;

use App\Catalog\Domain\Company\Company;
use App\Catalog\Domain\Company\CompanyRepository;
use App\Catalog\Domain\Company\Id;
use App\Catalog\Domain\Exception\CompanyNotFoundException;
use App\Catalog\Domain\Exception\CompanySaveFailedException;
use App\Shared\Domain\Email;
use Doctrine\DBAL\Connection;

final class DoctrineCompanyRepository implements CompanyRepository
{
    public function __construct(private Connection $connection)
    {
    }

    public function get(Id $id): Company
    {
        $company = $this->connection->fetchAssociative(
            'SELECT id, name, email FROM company WHERE id = :id',
            ['id' => (string) $id]
        );

        if (false === $company) {
            throw new CompanyNotFoundException((string) $id);
        }

        return new Company(
            Id::fromString($company['id']),
            new Email($company['email']),
            $company['email']
        );
    }

    public function save(Company $company): void
    {
        try {
            $result = $this->connection->executeStatement(
                "INSERT IGNORE INTO company(id, email, name) VALUE (:id, :email, :name)",
                [
                    'id' => (string) $company->getId(),
                    'email' => (string) $company->getEmail(),
                    'name' => $company->getName(),
                ]
            );
        } catch (\Throwable $exception) {
            throw new CompanySaveFailedException((string) $company->getId(), $exception);
        }

        if ($result === 0) {
            throw new CompanySaveFailedException((string) $company->getId());
        }
    }
}
