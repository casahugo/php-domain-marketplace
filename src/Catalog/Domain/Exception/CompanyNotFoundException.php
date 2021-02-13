<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Exception;

use Throwable;

final class CompanyNotFoundException extends DomainException
{
    public function __construct(string $id, Throwable $previous = null)
    {
        parent::__construct("Company #$id not found", 404, $previous);
    }
}
