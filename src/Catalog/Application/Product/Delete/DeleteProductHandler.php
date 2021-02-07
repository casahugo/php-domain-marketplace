<?php

declare(strict_types=1);

namespace App\Catalog\Application\Product\Delete;

use App\Catalog\Domain\Exception\DomainException;
use App\Catalog\Domain\Product\ProductRepository;
use App\Catalog\Domain\Product\Reference;
use App\Shared\Domain\Bus\Command\CommandHandlerInterface;
use App\Shared\Domain\Bus\Event\EventBus;
use App\Shared\Domain\Event\Product\ProductWasDeleted;

final class DeleteProductHandler implements CommandHandlerInterface
{
    private function __construct(private EventBus $eventBus, private ProductRepository $productRepository)
    {
    }

    /** @throws DomainException */
    public function __invoke(DeleteProductCommand $command): void
    {
        $product = $this->productRepository->get(Reference::fromString($command->getReference()));

        $product->record(new ProductWasDeleted($command->getReference()));

        $this->productRepository->delete($product);

        $this->eventBus->dispatch(...$product->pullDomainEvents());
    }
}
