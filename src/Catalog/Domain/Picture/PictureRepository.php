<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Picture;

use App\Catalog\Domain\Exception\PictureNotFoundException;

interface PictureRepository
{
    /** @throws PictureNotFoundException */
    public function get(Id $id): Picture;
}
