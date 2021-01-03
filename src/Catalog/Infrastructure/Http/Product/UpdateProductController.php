<?php

declare(strict_types=1);

namespace App\Catalog\Infrastructure\Http\Product;

use App\Catalog\Application\{
    Product\Update\UpdateProductCommand
};
use App\Shared\Domain\{
    Bus\Command\CommandBus,
};
use Symfony\Component\{
    HttpFoundation\JsonResponse,
    HttpFoundation\Request,
    OptionsResolver\Exception\InvalidArgumentException,
    OptionsResolver\OptionsResolver
};

final class UpdateProductController
{
    public function __construct(
        private CommandBus $commandBus,
    ) {
    }

    public function __invoke(string $productReference, Request $request): JsonResponse
    {
        $resolver = (new OptionsResolver())
            ->setRequired(['name', 'price', 'stock', 'categoryId'])
            ->setAllowedTypes('name', 'string')
            ->setAllowedTypes('price', 'float')
            ->setAllowedTypes('stock', 'int')
            ->setAllowedTypes('categoryId', 'int')
        ;

        try {
            $payload = $resolver->resolve($request->request->all());
        } catch (InvalidArgumentException $exception) {
            return new JsonResponse(['error' => $exception->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        }

        $this->commandBus->dispatch(new UpdateProductCommand(
            $productReference,
            $payload['name'],
            $payload['price'],
            $payload['stock'],
            $payload['categoryId'],
        ));

        return new JsonResponse(null, JsonResponse::HTTP_OK);
    }
}
