<?php

declare(strict_types=1);

namespace App\Catalog\Infrastructure\Http\Product;

use App\Catalog\Application\{
    Product\Create\CreateProductCommand
};
use App\Shared\Domain\{
    Bus\Command\CommandBus,
    Uuid\UuidGenerator
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
        private UuidGenerator $uuidGenerator
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $resolver = (new OptionsResolver())
            ->setRequired(['code', 'name', 'price', 'stock', 'brandId', 'categoryId', 'sellerId', 'taxes', 'shippings'])
            ->setAllowedTypes('code', 'string')
            ->setAllowedTypes('name', 'string')
            ->setAllowedTypes('price', 'float')
            ->setAllowedTypes('stock', 'int')
            ->setAllowedTypes('brandId', 'int')
            ->setAllowedTypes('categoryId', 'int')
            ->setAllowedTypes('sellerId', 'int')
            ->setAllowedTypes('taxes', 'string[]')
            ->setAllowedTypes('shippings', 'int[]')
        ;

        try {
            $payload = $resolver->resolve($request->request->all());
        } catch (InvalidArgumentException $exception) {
            return new JsonResponse(['error' => $exception->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        }

        $this->commandBus->dispatch(new CreateProductCommand(
            $reference = (string) $this->uuidGenerator->generate(),
            $payload['code'],
            $payload['name'],
            $payload['price'],
            $payload['stock'],
            $payload['brandId'],
            $payload['categoryId'],
            $payload['sellerId'],
            $payload['taxes'],
            $payload['shippings'],
            $payload['intro'] ?? null,
            $payload['description'] ?? null,
            isset($payload['originalPrice']) ? (float) $payload['originalPrice'] : null,
        ));

        return new JsonResponse(['reference' => $reference], JsonResponse::HTTP_CREATED);
    }
}
