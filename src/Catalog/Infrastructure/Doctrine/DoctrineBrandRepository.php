<?php

declare(strict_types=1);

namespace App\Catalog\Infrastructure\Doctrine;

use App\Catalog\Domain\Brand\Brand;
use App\Catalog\Domain\Brand\BrandRepository;
use App\Catalog\Domain\Brand\Id;

final class DoctrineBrandRepository implements BrandRepository
{
    public function get(Id $id): Brand
    {
        return new Brand($id, 'Samsung');
    }
}
