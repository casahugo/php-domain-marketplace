<?php

declare(strict_types=1);

namespace App\Catalog\Application\Product\Create;

use App\Catalog\Application\Picture\Upload\UploadPictureCommand;
use App\Shared\{
    Domain\Bus\Command\CommandBus,
    Domain\Bus\Command\CommandHandler,
    Domain\Bus\Event\EventBus,
    Domain\Clock\Clock,
    Domain\Uuid\UuidGenerator
};
use App\Catalog\Domain\{
    Brand,
    Brand\BrandRepository,
    Category\CategoryRepository,
    Category,
    Exception\DomainException,
    Picture\Id,
    Picture\Picture,
    Product\Product,
    Product\ProductRepository,
    Product\Status,
    Company,
    Company\CompanyRepository,
    Shipping,
    Shipping\ShippingRepository,
    Tax\Code,
    Tax\TaxRepository
};

final class CreateProductHandler implements CommandHandler
{
    public function __construct(
        private EventBus $eventBus,
        private CommandBus $commandBus,
        private ProductRepository $productRepository,
        private CategoryRepository $categoryRepository,
        private BrandRepository $brandRepository,
        private CompanyRepository $companyRepository,
        private TaxRepository $taxRepository,
        private ShippingRepository $shippingRepository,
        private UuidGenerator $uuidGenerator,
        private Clock $clock,
    ) {
    }

    /** @throws DomainException */
    public function __invoke(CreateProductCommand $command): void
    {
        $product = Product::create(
            $command->getReference(),
            $command->getCode(),
            $command->getName(),
            $command->getPrice(),
            $command->getStock(),
            $this->brandRepository->get(new Brand\Code($command->getBrandCode())),
            $this->companyRepository->get(Company\Id::fromString($command->getCompanyId())),
            $this->categoryRepository->get(new Category\Code($command->getCategoryCode())),
            $this->taxRepository->findByCodes(...array_map(
                static fn(string $code): Code => new Code($code),
                $command->getTaxCodes()
            )),
            Status::WAIT_MODERATION(),
            $this->clock->now(),
            null !== $command->getShippingCode()
                ? $this->shippingRepository->get(new Shipping\Code($command->getShippingCode()))
                : null,
            $command->getIntro(),
            $command->getDescription(),
            $command->getOriginalPrice()
        );

        foreach ($command->getPictures() as $path) {
            $id = $this->uuidGenerator->generate();
            $filename = pathinfo($path, PATHINFO_FILENAME);
            $extension = pathinfo($path, PATHINFO_EXTENSION);
            $mimeType = mime_content_type($path);
            $picture = new Picture(new Id($id), "$id/$filename.$extension", (string) $mimeType);

            $this->commandBus->dispatch(new UploadPictureCommand($path, $picture->getPath()));

            $product->addGallery($picture);
        }

        $this->productRepository->save($product);

        $this->eventBus->dispatch(...$product->pullDomainEvents());
    }
}
