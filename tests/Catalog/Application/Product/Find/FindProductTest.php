<?php

declare(strict_types=1);

namespace App\Tests\Catalog\Application\Product\Find;

use App\Catalog\Application\Product\Find\FindProduct;
use App\Catalog\Application\Product\Find\QueryProduct;
use App\Catalog\Domain\Exception\ProductNotFound;
use App\Catalog\Domain\Product\ProductRepository;
use App\Catalog\Domain\Product\Reference;
use App\Tests\Catalog\Factory;
use PHPUnit\Framework\TestCase;

final class FindProductTest extends TestCase
{
    public function testFind(): void
    {
        $handler = new FindProduct(
            $repository = $this->createMock(ProductRepository::class)
        );

        $repository
            ->expects(self::once())
            ->method('get')
            ->with(Reference::fromString(Factory::PRODUCT_REFERENCE))
            ->willReturn(Factory::getProduct());

        $handler(new QueryProduct(Factory::PRODUCT_REFERENCE));
    }

    public function testNotFound(): void
    {
        $this->expectException(ProductNotFound::class);
        $this->expectExceptionMessage("Product #01E439TP9XJZ9RPFH3T1PYBCR8 not found");
        $this->expectExceptionCode(404);

        $handler = new FindProduct(
            $repository = $this->createMock(ProductRepository::class)
        );

        $repository
            ->expects(self::once())
            ->method('get')
            ->with(Reference::fromString(Factory::PRODUCT_REFERENCE))
            ->willThrowException(new ProductNotFound(Factory::PRODUCT_REFERENCE));

        $handler(new QueryProduct(Factory::PRODUCT_REFERENCE));
    }
}
