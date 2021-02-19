<?php

declare(strict_types=1);

namespace App\Catalog\Infrastructure\Doctrine;

use App\Catalog\Domain\Tax\Code;
use App\Catalog\Domain\Tax\Tax;
use App\Catalog\Domain\Tax\TaxAmount;
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
        $taxes = $this->connection->fetchAllAssociative(
            "SELECT code, name, amount FROM tax WHERE code IN (:codes)",
            [
                ':codes' => array_map(static fn(Code $code): string => (string) $code, $codes),
            ],
            [
                ':codes' =>Connection::PARAM_STR_ARRAY,
            ]
        );

        return (new TaxCollection())->add(...array_map(
            static fn(array $tax) => new Tax(
                new Code($tax['code']),
                $tax['name'],
                new TaxAmount((float) $tax['amount'])
            ),
            $taxes
        ));
    }

    public function save(Tax $tax): void
    {
        $this->connection->insert('tax', [
           'code' => (string) $tax->getCode(),
           'name' => $tax->getName(),
           'amount' => $tax->getAmount()->getValue(),
        ]);
    }
}
