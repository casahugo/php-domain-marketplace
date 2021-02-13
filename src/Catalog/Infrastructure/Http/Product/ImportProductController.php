<?php

declare(strict_types=1);

namespace App\Catalog\Infrastructure\Http\Product;

use App\Catalog\Application\Import\Create\CreateImportProductCommand;
use App\Shared\Domain\Bus\Command\CommandBus;
use App\Shared\Domain\Uuid\UuidGenerator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final class ImportProductController
{
    public function __construct(
        private CommandBus $commandBus,
        private UuidGenerator $uuidGenerator
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        if (false === $request->files->has('file')) {
        }

        $importId = (string) $this->uuidGenerator->generate();
        $companyId = $request->request->get('companyId');

        $this->commandBus->dispatch(new CreateImportProductCommand(
            $importId,
            $companyId,
            $request->files->get('file')
        ));

        return new JsonResponse(['id' => $importId], JsonResponse::HTTP_CREATED);
    }
}
