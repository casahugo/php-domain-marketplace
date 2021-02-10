<?php

declare(strict_types=1);

namespace App\Catalog\Infrastructure\Doctrine;

use App\Catalog\Domain\Tax\Code;
use App\Catalog\Domain\Tax\TaxCollection;
use App\Catalog\Domain\Tax\TaxRepository;

final class DoctrineTaxRepository implements TaxRepository
{
    public function findByCode(Code ...$codes): TaxCollection
    {
        return new TaxCollection();
    }
}
