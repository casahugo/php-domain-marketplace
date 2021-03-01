<?php

declare(strict_types=1);

namespace App\Catalog\Application\Picture\Upload;

use App\Shared\Domain\Bus\Command\CommandHandler;
use App\Shared\Domain\Storage\FileStorage;

final class UploadPictureCommandHandler implements CommandHandler
{
    public function __construct(
        private FileStorage $storage
    ) {
    }

    public function __invoke(UploadPictureCommand $command): void
    {
        if (false === $this->storage->fileExists($command->getPath())) {
            $this->storage->write($command->getDestination(), $command->getPath());
        }
    }
}
