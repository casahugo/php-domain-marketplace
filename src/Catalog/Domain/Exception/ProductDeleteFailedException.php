<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Exception;

final class ProductDeleteFailedException extends DomainException
{
    public function __construct(string $reference, \Throwable $exception = null)
    {
        parent::__construct("Failed delete product #$reference", 500, $exception);
    }
}
