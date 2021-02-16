<?php

declare(strict_types=1);

namespace App\Catalog\Application\Tax\Create;

use App\Catalog\Domain\Tax\Code;
use App\Catalog\Domain\Tax\Tax;
use App\Catalog\Domain\Tax\TaxRepository;
use App\Catalog\Domain\Tax\TaxAmount;
use App\Shared\Domain\Bus\Command\CommandHandler;

final class CreateTaxCommandHandler implements CommandHandler
{
    public function __construct(private TaxRepository $repository)
    {
    }

    public function __invoke(CreateTaxCommand $command): void
    {
        $this->repository->save(new Tax(
            new Code($command->getCode()),
            $command->getName(),
            new TaxAmount($command->getAmount())
        ));
    }
}
