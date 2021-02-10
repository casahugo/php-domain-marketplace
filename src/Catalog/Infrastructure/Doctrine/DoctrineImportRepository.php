<?php

declare(strict_types=1);

namespace App\Catalog\Infrastructure\Doctrine;

use App\Catalog\Domain\Import\Import;
use App\Catalog\Domain\Import\ImportRepository;

final class DoctrineImportRepository implements ImportRepository
{
    public function save(Import $import): void
    {
        // TODO: Implement save() method.
    }
}
