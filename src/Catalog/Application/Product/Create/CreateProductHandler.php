<?php

declare(strict_types=1);

namespace App\Catalog\Application\Product\Create;

use App\Shared\{
    Domain\Bus\Event\EventBus,
    Domain\Clock\Clock
};
use App\Catalog\Domain\{
    Brand,
    Brand\BrandRepository,
    Category\CategoryRepository,
    Category,
    Exception\DomainException,
    Product\Product,
    Product\ProductPrice,
    Product\ProductRepository,
    Product\Status,
    Company,
    Company\CompanyRepository,
    Tax\Code,
    Tax\TaxRepository
};

final class CreateProductHandler
{
    public function __construct(
        private EventBus $eventBus,
        private ProductRepository $productRepository,
        private CategoryRepository $categoryRepository,
        private BrandRepository $brandRepository,
        private CompanyRepository $companyRepository,
        private TaxRepository $taxRepository,
        private Clock $clock,
    ) {
    }

    /** @throws DomainException */
    public function __invoke(CreateProductCommand $command): void
    {
        $category = $this->categoryRepository->get(new Category\Id($command->getCategoryId()));
        $brand = $this->brandRepository->get(new Brand\Id($command->getBrandId()));
        $seller = $this->companyRepository->get(new Company\Id($command->getCompanyId()));
        $taxes = $this->taxRepository->findByCode(...array_map(
            fn(string $code): Code => new Code($code),
            $command->getTaxCodes()
        ));

        $product = Product::create(
            $command->getReference(),
            $command->getCode(),
            $command->getName(),
            $command->getPrice(),
            $command->getStock(),
            $brand,
            $seller,
            $category,
            $taxes,
            Status::WAIT_MODERATION(),
            $this->clock->now(),
        );

        $product->setIntro($command->getIntro());
        $product->setDescription($command->getDescription());

        if (null !== $command->getOriginalPrice()) {
            $product->setOriginalPrice(new ProductPrice($command->getOriginalPrice()));
        }

        $this->productRepository->save($product);

        $this->eventBus->dispatch(...$product->pullDomainEvents());
    }
}
