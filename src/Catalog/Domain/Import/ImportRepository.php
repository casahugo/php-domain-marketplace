<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Import;

use App\Catalog\Domain\Exception\ImportSaveFailedException;

interface ImportRepository
{
    /** @throws ImportSaveFailedException */
    public function save(Import $import): void;
}
