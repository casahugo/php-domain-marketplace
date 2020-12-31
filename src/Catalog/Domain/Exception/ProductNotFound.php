<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Exception;

final class ProductNotFound extends DomainException
{
    public function __construct(string $reference)
    {
        parent::__construct("Product #$reference not found");
    }
}
