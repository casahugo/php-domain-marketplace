<?php

declare(strict_types=1);

namespace App\Catalog\Application\Product\Create;

use App\Shared\{
    Domain\Bus\Event\EventBus,
    Domain\Clock\ClockInterface
};
use App\Catalog\Domain\{
    Brand,
    Brand\BrandRepository,
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
        private BrandRepository $brandRepository,
        private SellerRepository $sellerRepository,
        private ClockInterface $clock,
    ) {
    }

    /** @throws DomainException */
    public function __invoke(CreateProductCommand $command): void
    {
        $category = $this->categoryRepository->get(new Category\Id($command->getCategoryId()));
        $brand = $this->brandRepository->get(new Brand\Id($command->getBrandId()));
        $seller = $this->sellerRepository->get(new Seller\Id($command->getSellerId()));

        $product = Product::create(
            $command->getReference(),
            $command->getCode(),
            $command->getName(),
            $command->getPrice(),
            $command->getStock(),
            $brand,
            $seller,
            $category,
            new TaxCollection(),
            Status::WAIT_MODERATION(),
            $this->clock->now(),
        );

        $this->productRepository->save($product);

        $this->eventBus->dispatch(...$product->pullDomainEvents());
    }
}
