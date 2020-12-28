<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Seller;

use App\Catalog\Domain\Exception\SellerNotFoundException;

interface SellerRepository
{
    /** @throws SellerNotFoundException */
    public function get(Id $id): Seller;
}
