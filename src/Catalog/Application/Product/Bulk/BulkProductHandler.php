<?php

declare(strict_types=1);

namespace App\Catalog\Application\Product\Bulk;

use App\Catalog\Application\Product\Create\CreateProductCommand;
use App\Catalog\Domain\Exception\ProductSaveFailedException;
use App\Catalog\Domain\Product\Code;
use App\Catalog\Domain\Product\ProductRepository;
use App\Shared\Domain\Bus\Command\CommandBus;
use App\Shared\Domain\Bus\Command\CommandHandler;
use App\Shared\Domain\Logger\Logger;
use App\Shared\Domain\Uuid\UuidGenerator;

final class BulkProductHandler implements CommandHandler
{
    public function __construct(
        private CommandBus $commandBus,
        private ProductRepository $repository,
        private Logger $logger,
        private UuidGenerator $uuidGenerator,
    ) {
    }

    public function __invoke(BulkProductCommand $command): void
    {
        $products = $this->repository->findByCode(...array_map(
            fn(string $code): Code => new Code($code),
            $command->getProductsCode()
        ));

        foreach ($command->getProducts() as $productImport) {
            try {
                if ($product = $products->findByCode(new Code($productImport['code']))) {
                    // ... update manually
                    $t = $product;
                } else {
                    $this->commandBus->dispatch(new CreateProductCommand(
                        (string) $this->uuidGenerator->generate(),
                        $productImport['code'],
                        $productImport['name'],
                        $productImport['price'],
                        $productImport['stock'],
                        $productImport['brandId'],
                        $productImport['categoryId'],
                        $command->getCompanyId(),
                        $productImport['taxes'],
                        $productImport['shippings'] ?? null,
                        $productImport['intro'] ?? null,
                        $productImport['description'] ?? null,
                        isset($productImport['originalPrice']) ? (float) $productImport['originalPrice'] : null,
                    ));
                }
            } catch (ProductSaveFailedException $exception) {
                $this->logger->error($exception->getMessage());
            }
        }
    }
}
