<?php

declare(strict_types=1);

namespace App\Catalog\Application\Brand\Create;

use App\Catalog\Domain\Brand\Brand;
use App\Catalog\Domain\Brand\BrandRepository;
use App\Catalog\Domain\Brand\Code;
use App\Shared\Domain\Bus\Command\CommandHandler;

final class CreateBrandHandler implements CommandHandler
{
    public function __construct(private BrandRepository $repository)
    {
    }

    public function __invoke(CreateBrandCommand $command): void
    {
        $brand = new Brand(new Code($command->getCode()), $command->getName());

        $this->repository->save($brand);
    }
}
