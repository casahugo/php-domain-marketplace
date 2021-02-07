<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Exception;

final class ProductDeleteFailedException extends DomainException
{
    public function __construct(string $reference)
    {
        parent::__construct("Failed delete product #$reference");
    }
}
