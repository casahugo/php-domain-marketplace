<?php

declare(strict_types=1);

namespace App\Catalog\Application\Import\Create;

use App\Catalog\{
    Application\Product\Bulk\BulkProductCommand,
    Domain\Exception\DomainException,
    Domain\Import\Import,
    Domain\Import\ImportReader,
    Domain\Import\ImportRepository,
};
use App\Shared\{
    Domain\Bus\Command\CommandBus,
    Domain\Bus\Command\CommandHandler,
    Domain\Storage\FileStorage
};

final class CreateImportProductHandler implements CommandHandler
{
    private const BULK_SIZE = 5;

    public function __construct(
        private CommandBus $commandBus,
        private ImportRepository $repository,
        private ImportReader $reader,
        private FileStorage $storage
    ) {
    }

    /** @throws DomainException */
    public function __invoke(CreateImportProductCommand $command): void
    {
        $import = Import::create($command->getImportId(), $command->getFilePath());

        $this->storage->copy($import->getFilePath(), 'product.csv');

        $this->repository->save($import);

        $bulk = $this->reader->read($import->getFilePath());

        foreach ($bulk->chunk(self::BULK_SIZE) as $products) {
            $this->commandBus->dispatch(new BulkProductCommand(
                $command->getImportId(),
                $command->getCompanyId(),
                $products
            ));
        }
    }
}
