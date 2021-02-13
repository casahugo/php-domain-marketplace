<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Company;

use App\Catalog\Domain\Exception\CompanyNotFoundException;
use App\Catalog\Domain\Exception\CompanySaveFailedException;

interface CompanyRepository
{
    /** @throws CompanyNotFoundException */
    public function get(Id $id): Company;

    /** @throws CompanySaveFailedException */
    public function save(Company $company): void;
}
