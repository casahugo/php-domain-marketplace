<?php

declare(strict_types=1);

namespace App\Tests\Catalog\Domain\Tax;

use App\Catalog\Domain\Tax\TaxValue;
use PHPUnit\Framework\TestCase;

final class TaxValueTest extends TestCase
{
    public function testCastIntFloat(): void
    {
        self::assertSame(2., (new TaxValue(2.))->getValue());
        self::assertSame(2., (new TaxValue(2))->getValue());
    }

    /** @dataProvider invalidValueProvider */
    public function testValueGreaterThanZero(int|float $value): void
    {
        $this->expectException(\LogicException::class);

        new TaxValue($value);
    }

    public function invalidValueProvider(): \Generator
    {
        yield [0];
        yield [0.];
        yield [-1];
        yield [-1.2334];
    }
}
