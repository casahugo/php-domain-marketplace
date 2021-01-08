<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Import;

final class Import
{
    public function __construct(private Id $importId, private string $filePath)
    {
    }

    public static function create(string $importId, string $filePath): self
    {
        return new self(Id::fromString($importId), $filePath);
    }

    public function getFilePath(): string
    {
        return $this->filePath;
    }
}
