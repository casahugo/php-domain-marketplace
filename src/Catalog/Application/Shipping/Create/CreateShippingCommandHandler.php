<?php

declare(strict_types=1);

namespace App\Catalog\Application\Shipping\Create;

use App\Catalog\Domain\Shipping\Code;
use App\Catalog\Domain\Shipping\Shipping;
use App\Catalog\Domain\Shipping\ShippingPrice;
use App\Catalog\Domain\Shipping\ShippingRepository;
use App\Shared\Domain\Bus\Command\CommandHandler;

final class CreateShippingCommandHandler implements CommandHandler
{
    public function __construct(private ShippingRepository $repository)
    {
    }

    public function __invoke(CreateShippingCommand $command): void
    {
        $this->repository->save(new Shipping(
            new Code($command->getCode()),
            $command->getName(),
            new ShippingPrice($command->getPrice())
        ));
    }
}
