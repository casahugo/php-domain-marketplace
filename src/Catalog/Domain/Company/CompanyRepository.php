<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Company;

use App\Catalog\Domain\Exception\CompanyNotFoundException;

interface CompanyRepository
{
    /** @throws CompanyNotFoundException */
    public function get(Id $id): Company;
}
