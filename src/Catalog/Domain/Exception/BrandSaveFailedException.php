<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Exception;

final class BrandSaveFailedException extends DomainException
{
    public function __construct(string $code)
    {
        parent::__construct("Failed save brand #$code");
    }
}
