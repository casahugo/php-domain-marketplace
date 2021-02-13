<?php

declare(strict_types=1);

namespace App\Catalog\Application\Company\Create;

use App\Catalog\Domain\Company\Company;
use App\Catalog\Domain\Company\CompanyRepository;
use App\Catalog\Domain\Company\Id;
use App\Shared\Domain\Bus\Command\CommandHandler;
use App\Shared\Domain\Email;

final class CreateCompanyCommandHandler implements CommandHandler
{
    public function __construct(private CompanyRepository $repository)
    {
    }

    public function __invoke(CreateCompanyCommand $command): void
    {
        $company = new Company(
            Id::fromString($command->getId()),
            new Email($command->getEmail()),
            $command->getName()
        );

        $this->repository->save($company);
    }
}
