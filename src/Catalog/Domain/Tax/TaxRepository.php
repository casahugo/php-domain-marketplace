<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Tax;

interface TaxRepository
{
    public function findByCode(Code ...$codes): TaxCollection;

    public function save(Tax $tax): void;
}
