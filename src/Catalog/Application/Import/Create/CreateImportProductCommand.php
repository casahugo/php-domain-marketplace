<?php

declare(strict_types=1);

namespace App\Catalog\Application\Import\Create;

use App\Shared\Domain\Bus\Command\DomainCommand;

final class CreateImportProductCommand implements DomainCommand
{
    public function __construct(private string $importId, private int $companyId, private string $filePath)
    {
    }

    public function getImportId(): string
    {
        return $this->importId;
    }

    public function getCompanyId(): int
    {
        return $this->companyId;
    }

    public function getFilePath(): string
    {
        return $this->filePath;
    }
}
