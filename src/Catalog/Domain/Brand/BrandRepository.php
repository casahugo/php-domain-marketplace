<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Brand;

interface BrandRepository
{
    public function get(Id $brand): Brand;
}
