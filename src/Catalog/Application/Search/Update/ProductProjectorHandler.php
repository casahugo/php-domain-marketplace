<?php

declare(strict_types=1);

namespace App\Catalog\Application\Search\Update;

use App\Catalog\Domain\Product\ProductProjector;
use App\Catalog\Domain\Product\ProductRepository;
use App\Catalog\Domain\Product\Reference;
use App\Catalog\Domain\Product\Status;
use App\Shared\Domain\Bus\Event\EventHandler;
use App\Shared\Domain\Event\Product\ProductHasChanged;

final class ProductProjectorHandler implements EventHandler
{
    private ProductRepository $productRepository;
    private array $productProjector;

    public function __construct(
        ProductRepository $productRepository,
        ProductProjector ...$productProjector
    ) {
        $this->productRepository = $productRepository;
        $this->productProjector = $productProjector;
    }

    public function __invoke(ProductHasChanged $event): void
    {
        $product = $this->productRepository->get(Reference::fromString($event->getProductReference()));

        if ($product->getStatus()->equals(Status::ENABLED())) {
            foreach ($this->productProjector as $projector) {
                $projector->create($product);
            }
        } else {
            foreach ($this->productProjector as $projector) {
                $projector->delete($product);
            }
        }
    }
}
