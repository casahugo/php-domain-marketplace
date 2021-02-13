<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Brand;

use App\Catalog\Domain\Exception\BrandNotFoundException;
use App\Catalog\Domain\Exception\BrandSaveFailedException;

interface BrandRepository
{
    /** @throws BrandNotFoundException */
    public function get(Code $id): Brand;

    /** @throws BrandSaveFailedException */
    public function save(Brand $brand): void;
}
