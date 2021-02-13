<?php

declare(strict_types=1);

namespace App\Catalog\Infrastructure\Import;

use App\Catalog\Domain\Import\BulkCollection;
use App\Catalog\Domain\Import\ImportReader;

final class CsvReader implements ImportReader
{
    public function read(string $file): BulkCollection
    {
        return new BulkCollection();
    }
}
