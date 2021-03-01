<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Exception;

final class PictureNotFoundException extends DomainException
{
    public function __construct(string $code)
    {
        parent::__construct("Picture #$code not found", 404);
    }
}
