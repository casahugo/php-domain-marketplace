<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Import;

interface ImportStorage
{
    public function put(mixed $file): void;
}
