<?php

declare(strict_types=1);

namespace App\Catalog\Infrastructure\Doctrine;

use App\Catalog\Domain\Company\Company;
use App\Catalog\Domain\Company\CompanyRepository;
use App\Catalog\Domain\Company\Id;
use App\Shared\Domain\Email;

final class DoctrineCompanyRepository implements CompanyRepository
{
    public function get(Id $id): Company
    {
        return new Company($id, new Email('foo.bar@gmail.com'), 'Inc Corporation');
    }
}
