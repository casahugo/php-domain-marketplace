<?php

declare(strict_types=1);

namespace App\Catalog\Application\Search\Update;

use App\Catalog\Domain\Product\ProductProjector;
use App\Catalog\Domain\Product\ProductRepository;
use App\Catalog\Domain\Product\Reference;
use App\Catalog\Domain\Product\Status;
use App\Shared\Domain\Bus\Event\EventHandlerInterface;
use App\Shared\Domain\Event\Product\ProductHasChanged;

final class ProductProjectorHandler implements EventHandlerInterface
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
        $product = $this->productRepository->get(new Reference($event->getProductReference()));

        if ($product->getStatus()->equals(Status::ENABLED())) {
            foreach ($this->productProjector as $projector) {
                $projector($product);
            }
        }
    }
}
