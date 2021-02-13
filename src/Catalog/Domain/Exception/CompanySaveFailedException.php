<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Exception;

final class CompanySaveFailedException extends DomainException
{
    public function __construct(string $code, \Throwable $exception = null)
    {
        parent::__construct("Failed save company #$code", 500, $exception);
    }
}
