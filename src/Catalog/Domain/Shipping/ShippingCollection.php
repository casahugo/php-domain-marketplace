<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Shipping;

use App\Shared\Domain\DataStructure\Collection;

final class ShippingCollection extends Collection
{
    public function __construct()
    {
        parent::__construct(Shipping::class);
    }
}
