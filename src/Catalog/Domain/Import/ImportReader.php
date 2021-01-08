<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Import;

interface ImportReader
{
    public function read(string $file): BulkCollection;
}
