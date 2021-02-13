<?php

declare(strict_types=1);

namespace App\Tests\Catalog\Application\Company\Create;

use App\Catalog\Application\Company\Create\CreateCompanyCommand;
use App\Catalog\Application\Company\Create\CreateCompanyEventHandler;
use App\Shared\Domain\Bus\Command\CommandBus;
use App\Shared\Domain\Event\Seller\SellerWasCreated;
use App\Tests\Catalog\Factory;
use PHPUnit\Framework\TestCase;

final class CreateCompanyEventHandlerTest extends TestCase
{
    public function testEventCreate(): void
    {
        $handler = new CreateCompanyEventHandler(
            $commandBus = $this->createMock(CommandBus::class)
        );

        $commandBus
            ->expects(self::once())
            ->method('dispatch')
            ->with(new CreateCompanyCommand(Factory::COMPANY_ID, Factory::COMPANY_EMAIL, Factory::COMPANY_NAME));

        $handler(new SellerWasCreated(Factory::COMPANY_ID, Factory::COMPANY_EMAIL, Factory::COMPANY_NAME));
    }
}
