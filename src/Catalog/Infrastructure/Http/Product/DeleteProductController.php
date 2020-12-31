<?php

declare(strict_types=1);

namespace App\Catalog\Infrastructure\Http\Product;

use App\Shared\Infrastructure\Uuid\Uuid;
use App\Catalog\Application\{
    Product\Delete\DeleteProductCommand
};
use App\Shared\Domain\{
    Bus\Command\CommandBus,
};
use Symfony\Component\{
    HttpFoundation\JsonResponse,
};

final class DeleteProductController
{
    public function __construct(
        private CommandBus $commandBus,
    ) {
    }

    public function __invoke(Uuid $productReference): JsonResponse
    {
        $this->commandBus->dispatch(new DeleteProductCommand($productReference));

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
