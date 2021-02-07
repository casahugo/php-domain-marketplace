<?php

declare(strict_types=1);

namespace App\Catalog\Application\Search\Create;

use App\Shared\Domain\Bus\Event\EventHandlerInterface;
use App\Shared\Domain\Event\Product\ProductWasCreated;

final class SearchProductWasCreatedHandler implements EventHandlerInterface
{
    public function __invoke(ProductWasCreated $created)
    {

    }
}
