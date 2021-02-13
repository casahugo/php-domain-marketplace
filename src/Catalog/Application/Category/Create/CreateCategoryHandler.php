<?php

declare(strict_types=1);

namespace App\Catalog\Application\Category\Create;

use App\Catalog\Domain\Category\Category;
use App\Catalog\Domain\Category\CategoryRepository;
use App\Catalog\Domain\Category\Code;
use App\Shared\Domain\Bus\Command\CommandHandler;

final class CreateCategoryHandler implements CommandHandler
{
    public function __construct(private CategoryRepository $repository)
    {
    }

    public function __invoke(CreateCategoryCommand $command): void
    {
        $category = new Category(new Code($command->getCode()), $command->getName());

        $this->repository->save($category);
    }
}
