<?php

declare(strict_types=1);

namespace App\Catalog\Infrastructure\Doctrine;

use App\Catalog\Domain\Tax\Code;
use App\Catalog\Domain\Tax\Tax;
use App\Catalog\Domain\Tax\TaxCollection;
use App\Catalog\Domain\Tax\TaxRepository;
use Doctrine\DBAL\Connection;

final class DoctrineTaxRepository implements TaxRepository
{
    public function __construct(private Connection $connection)
    {
    }

    public function findByCode(Code ...$codes): TaxCollection
    {
        return new TaxCollection();
    }

    public function save(Tax $tax): void
    {
        $this->connection->insert('tax', [
           'code' => (string) $tax->getCode(),
           'name' => $tax->getName(),
           'amount' => $tax->getTaxAmount()->getValue(),
        ]);
    }
}
