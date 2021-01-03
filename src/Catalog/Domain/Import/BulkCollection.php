<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Import;

class BulkCollection extends \ArrayIterator
{
    public function chunk(int $size): \Generator
    {
        $chunk = [];

        for ($i = 0; $this->valid(); $i++) {
            $chunk[] = $this->current();
            $this->next();
            if (count($chunk) == $size) {
                yield $chunk;
                $chunk = [];
            }
        }

        if (count($chunk)) {
            yield $chunk;
        }
    }
}
