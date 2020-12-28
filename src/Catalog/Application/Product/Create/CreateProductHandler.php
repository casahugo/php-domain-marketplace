<?php

declare(strict_types=1);

namespace App\Catalog\Application\Product\Create;

use App\Shared\{
    Domain\Bus\Event\EventBus,
};
use App\Catalog\Domain\{
    Category\CategoryRepository,
    Category,
    Exception\DomainException,
    Product\Product,
    Product\ProductRepository,
    Product\Status,
    Seller,
    Seller\SellerRepository,
    Tax\TaxCollection
};

final class CreateProductHandler
{
    public function __construct(
        private EventBus $eventBus,
        private ProductRepository $productRepository,
        private CategoryRepository $categoryRepository,
        private SellerRepository $sellerRepository,
    ) {
    }

    /** @throws DomainException */
    public function __invoke(CreateProductCommand $command): void
    {
        $category = $this->categoryRepository->get(new Category\Id($command->getCategoryId()));
        $seller = $this->sellerRepository->get(new Seller\Id($command->getSellerId()));

        $product = Product::create(
            $command->getReference(),
            $command->getCode(),
            $command->getName(),
            $command->getPrice(),
            $command->getStock(),
            $seller,
            $category,
            new TaxCollection(),
            Status::WAIT_MODERATION()
        );

        $this->productRepository->save($product);

        $this->eventBus->dispatch(...$product->pullDomainEvents());
    }
}
