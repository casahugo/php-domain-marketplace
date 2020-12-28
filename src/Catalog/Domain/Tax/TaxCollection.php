<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Tax;

use App\Shared\Domain\DataStructure\Collection;

final class TaxCollection extends Collection
{
    public function __construct()
    {
        parent::__construct(Tax::class);
    }
}
