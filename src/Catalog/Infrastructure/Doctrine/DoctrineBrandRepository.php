<?php

declare(strict_types=1);

namespace App\Catalog\Infrastructure\Doctrine;

use App\Catalog\Domain\Brand\Brand;
use App\Catalog\Domain\Brand\BrandRepository;
use App\Catalog\Domain\Brand\Code;
use App\Catalog\Domain\Exception\BrandNotFoundException;
use App\Catalog\Domain\Exception\BrandSaveFailedException;
use Doctrine\DBAL\Connection;

final class DoctrineBrandRepository implements BrandRepository
{
    public function __construct(private Connection $connection)
    {
    }

    public function get(Code $code): Brand
    {
        $brand = $this->connection->fetchAssociative("SELECT code, name FROM brand WHERE code = :code", [
            'code' => (string) $code,
        ]);

        if (false === $brand) {
            throw new BrandNotFoundException("Brand #$code not found", 404);
        }

        return new Brand($code, $brand['name']);
    }

    public function save(Brand $brand): void
    {
        try {
            $result = $this->connection->executeStatement(
                "INSERT IGNORE INTO brand(code, name) VALUE (:code, :name)",
                [
                    'code' => (string) $brand->getCode(),
                    'name' => $brand->getName(),
                ]
            );
        } catch (\Exception $exception) {
            throw new BrandSaveFailedException((string) $brand->getCode(), $exception);
        }

        if ($result === 0) {
            throw new BrandSaveFailedException((string) $brand->getCode());
        }
    }
}
