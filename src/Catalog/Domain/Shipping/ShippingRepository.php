<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Shipping;

use App\Catalog\Domain\Exception\ShippingNotFoundException;
use App\Catalog\Domain\Exception\ShippingSaveFailedException;

interface ShippingRepository
{
    /** @throws ShippingNotFoundException */
    public function get(Code $code): Shipping;

    /** @throws ShippingSaveFailedException */
    public function save(Shipping $shipping): void;
}
