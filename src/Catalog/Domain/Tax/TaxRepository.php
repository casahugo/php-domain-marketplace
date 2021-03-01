<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Tax;

interface TaxRepository
{
    public function findByCodes(Code ...$codes): TaxCollection;

    public function save(Tax $tax): void;
}
