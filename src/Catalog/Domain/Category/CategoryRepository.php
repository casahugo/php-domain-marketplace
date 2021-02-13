<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Category;

use App\Catalog\Domain\Exception\CategoryNotFoundException;
use App\Catalog\Domain\Exception\CategorySaveFailedException;

interface CategoryRepository
{
    /** @throws CategoryNotFoundException */
    public function get(Code $code): Category;

    /** @throws CategorySaveFailedException */
    public function save(Category $category): void;
}
