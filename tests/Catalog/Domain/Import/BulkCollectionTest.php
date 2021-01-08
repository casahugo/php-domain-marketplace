<?php

declare(strict_types=1);

namespace App\Tests\Catalog\Domain\Import;

use App\Catalog\Domain\Import\BulkCollection;
use PHPUnit\Framework\TestCase;

final class BulkCollectionTest extends TestCase
{
    public function testChunkSize(): void
    {
        $bulk = new BulkCollection([
            'foo',
            'bar',
            'baz',
        ]);

        $chunk = $bulk->chunk(2);

        self::assertSame(['foo', 'bar'], $chunk->current());
        self::assertCount(2, $chunk);
    }
}
