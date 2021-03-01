<?php

declare(strict_types=1);

namespace App\Catalog\Application\Picture\Upload;

use App\Shared\Domain\Bus\Command\DomainCommand;

final class UploadPictureCommand implements DomainCommand
{
    public function __construct(private string $path, private string $destination)
    {
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getDestination(): string
    {
        return $this->destination;
    }
}
