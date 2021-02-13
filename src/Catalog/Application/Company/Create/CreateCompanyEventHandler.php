<?php

declare(strict_types=1);

namespace App\Catalog\Application\Company\Create;

use App\Shared\Domain\Bus\Command\CommandBus;
use App\Shared\Domain\Bus\Event\EventHandler;
use App\Shared\Domain\Event\Seller\SellerWasCreated;

final class CreateCompanyEventHandler implements EventHandler
{
    public function __construct(private CommandBus $commandBus)
    {
    }

    public function __invoke(SellerWasCreated $seller): void
    {
        $this->commandBus->dispatch(new CreateCompanyCommand(
            $seller->getId(),
            $seller->getEmail(),
            $seller->getName()
        ));
    }
}
