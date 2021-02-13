<?php

declare(strict_types=1);

namespace App\Catalog\Application\Search\Create;

use App\Catalog\Domain\Product\ProductProjector;
use App\Catalog\Domain\Product\ProductRepository;
use App\Catalog\Domain\Product\Reference;
use App\Shared\Domain\Bus\Event\EventHandler;
use App\Shared\Domain\Event\Product\ProductWasCreated;

final class SearchProductCreatedHandler implements EventHandler
{
    public function __construct(private ProductRepository $repository, private ProductProjector $projector)
    {
    }

    public function __invoke(ProductWasCreated $created): void
    {
        $product = $this->repository->get(Reference::fromString($created->getProductReference()));

        $this->projector->create($product);
    }
}
