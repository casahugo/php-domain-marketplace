<?php

declare(strict_types=1);

namespace App\Catalog\Infrastructure\Controller\Product;

use App\Catalog\Application\{
    Product\Create\CreateProductCommand
};
use App\Shared\Domain\{
    Bus\Command\CommandBus,
    Uuid\UuidGeneratorInterface
};
use Symfony\Component\{
    HttpFoundation\JsonResponse,
    HttpFoundation\Request,
    OptionsResolver\Exception\InvalidArgumentException,
    OptionsResolver\OptionsResolver
};

final class CreateProductController
{
    public function __construct(
        private CommandBus $commandBus,
        private UuidGeneratorInterface $uuidGenerator
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $resolver = (new OptionsResolver())
            ->setRequired(['code', 'name', 'price', 'stock', 'categoryId', 'sellerId'])
            ->setAllowedTypes('code', 'string')
            ->setAllowedTypes('name', 'string')
            ->setAllowedTypes('price', 'float')
            ->setAllowedTypes('stock', 'int')
            ->setAllowedTypes('categoryId', 'int')
            ->setAllowedTypes('sellerId', 'int');

        try {
            $payload = $resolver->resolve($request->request->all());
        } catch (InvalidArgumentException $exception) {
            return new JsonResponse(['error' => $exception->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        }

        $this->commandBus->dispatch(new CreateProductCommand(
            $reference = $this->uuidGenerator->generate(),
            $payload['code'],
            $payload['name'],
            $payload['price'],
            $payload['stock'],
            $payload['categoryId'],
            $payload['sellerId'],
        ));

        return new JsonResponse(['reference' => (string) $reference], JsonResponse::HTTP_CREATED);
    }
}