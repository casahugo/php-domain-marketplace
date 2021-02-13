<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Exception;

final class CategorySaveFailedException extends DomainException
{
    public function __construct(string $code, \Throwable $exception = null)
    {
        parent::__construct("Failed save category #$code", 500, $exception);
    }
}
