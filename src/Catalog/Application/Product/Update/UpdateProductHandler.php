<?php

declare(strict_types=1);

namespace App\Catalog\Application\Product\Update;

use App\Catalog\Domain\Category\CategoryRepository;
use App\Catalog\Domain\Category\Id;
use App\Catalog\Domain\Exception\DomainException;
use App\Catalog\Domain\Product\ProductPrice;
use App\Catalog\Domain\Product\ProductRepository;
use App\Catalog\Domain\Product\Reference;
use App\Catalog\Domain\Product\Stock;
use App\Shared\Domain\Bus\Event\EventBus;
use App\Shared\Domain\Event\Product\ProductHasChanged;

final class UpdateProductHandler
{
    public function __construct(
        private EventBus $eventBus,
        private ProductRepository $productRepository,
        private CategoryRepository $categoryRepository,
    ) {
    }

    /** @throws DomainException */
    public function __invoke(UpdateProductCommand $command): void
    {
        $product = $this->productRepository->get(Reference::fromString($command->getReference()));
        $category = $this->categoryRepository->get(new Id($command->getCategoryId()));

        $product
            ->setPrice(new ProductPrice($command->getPrice()))
            ->setStock(new Stock($command->getStock()))
            ->setName($command->getName())
            ->setCategory($category)
        ;

        $product->record(new ProductHasChanged($command->getReference()));

        $this->eventBus->dispatch(...$product->pullDomainEvents());
    }
}
