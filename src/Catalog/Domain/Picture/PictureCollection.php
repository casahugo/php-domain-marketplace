<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Picture;

use App\Shared\Domain\DataStructure\Collection;

final class PictureCollection extends Collection
{
    public function __construct()
    {
        parent::__construct(Picture::class);
    }
}
