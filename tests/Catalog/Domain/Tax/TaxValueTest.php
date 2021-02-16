<?php

declare(strict_types=1);

namespace App\Tests\Catalog\Domain\Tax;

use App\Catalog\Domain\Tax\TaxAmount;
use PHPUnit\Framework\TestCase;

final class TaxValueTest extends TestCase
{
    public function testCastIntFloat(): void
    {
        self::assertSame(2., (new TaxAmount(2.))->getValue());
        self::assertSame(2., (new TaxAmount(2))->getValue());
    }

    /** @dataProvider invalidValueProvider */
    public function testValueGreaterThanZero(int|float $value): void
    {
        $this->expectException(\LogicException::class);

        new TaxAmount($value);
    }

    public function invalidValueProvider(): \Generator
    {
        yield [0];
        yield [0.];
        yield [-1];
        yield [-1.2334];
    }
}
