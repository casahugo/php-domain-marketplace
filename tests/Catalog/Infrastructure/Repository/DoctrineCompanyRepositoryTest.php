<?php

declare(strict_types=1);

namespace App\Tests\Catalog\Infrastructure\Repository;

use App\Catalog\Domain\Company\Company;
use App\Catalog\Domain\Company\Id;
use App\Catalog\Infrastructure\Doctrine\DoctrineCompanyRepository;
use App\Tests\Catalog\Factory;
use PHPUnit\Framework\TestCase;

final class DoctrineCompanyRepositoryTest extends TestCase
{
    public function testGet(): void
    {
        $repository = new DoctrineCompanyRepository();

        $company = $repository->get(Id::fromString(Factory::COMPANY_ID));

        self::assertInstanceOf(Company::class, $company);
    }
}
