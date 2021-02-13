<?php

declare(strict_types=1);

namespace App\Tests\Catalog\Application\Company\Create;

use App\Catalog\Application\Company\Create\CreateCompanyCommand;
use App\Catalog\Application\Company\Create\CreateCompanyCommandHandler;
use App\Catalog\Domain\Company\CompanyRepository;
use App\Tests\Catalog\Factory;
use PHPUnit\Framework\TestCase;

final class CreateCompanyCommandHandlerTest extends TestCase
{
    public function testCreate(): void
    {
        $handler = new CreateCompanyCommandHandler(
            $repository = $this->createMock(CompanyRepository::class)
        );

        $repository
            ->expects(self::once())
            ->method('save')
            ->with(Factory::getCompany());

        $handler(new CreateCompanyCommand(
            Factory::COMPANY_ID,
            Factory::COMPANY_EMAIL,
            Factory::COMPANY_NAME
        ));
    }
}
