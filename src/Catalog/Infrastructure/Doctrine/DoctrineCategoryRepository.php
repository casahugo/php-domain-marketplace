<?php

declare(strict_types=1);

namespace App\Catalog\Infrastructure\Doctrine;

use App\Catalog\Domain\Category\Category;
use App\Catalog\Domain\Category\CategoryRepository;
use App\Catalog\Domain\Category\Id;

final class DoctrineCategoryRepository implements CategoryRepository
{
    public function get(Id $id): Category
    {
        return new Category($id, 'Laptor');
    }
}
