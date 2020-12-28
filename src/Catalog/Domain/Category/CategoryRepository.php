<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Category;

use App\Catalog\Domain\Exception\CategoryNotFoundException;

interface CategoryRepository
{
    /** @throws CategoryNotFoundException */
    public function get(Id $id): Category;
}
