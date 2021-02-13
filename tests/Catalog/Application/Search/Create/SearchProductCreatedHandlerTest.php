<?php

declare(strict_types=1);

namespace App\Tests\Catalog\Application\Search\Create;

use App\Catalog\Application\Search\Create\SearchProductCreatedHandler;
use App\Catalog\Domain\Exception\ProductNotFound;
use App\Catalog\Domain\Product\ProductProjector;
use App\Catalog\Domain\Product\ProductRepository;
use App\Catalog\Domain\Product\Reference;
use App\Shared\Domain\Event\Product\ProductWasCreated;
use App\Tests\Catalog\Factory;
use PHPUnit\Framework\TestCase;

final class SearchProductCreatedHandlerTest extends TestCase
{
    public function testCreateSearchProduct(): void
    {
        $handler = new SearchProductCreatedHandler(
            $repository = $this->createMock(ProductRepository::class),
            $projector = $this->createMock(ProductProjector::class),
        );

        $repository
            ->expects(self::once())
            ->method('get')
            ->with(Reference::fromString(Factory::PRODUCT_REFERENCE))
            ->willReturn($product = Factory::getProduct(Factory::PRODUCT_REFERENCE));

        $projector
            ->expects(self::once())
            ->method('create')
            ->with($product);

        $handler(new ProductWasCreated(Factory::PRODUCT_REFERENCE));
    }

    public function testCreateSearchProductNotFound(): void
    {
        $this->expectException(ProductNotFound::class);
        $this->expectExceptionMessage('Product #01E439TP9XJZ9RPFH3T1PYBCR8 not found');
        $this->expectExceptionCode(404);

        $handler = new SearchProductCreatedHandler(
            $repository = $this->createMock(ProductRepository::class),
            $projector = $this->createMock(ProductProjector::class),
        );

        $repository
            ->expects(self::once())
            ->method('get')
            ->with(Reference::fromString(Factory::PRODUCT_REFERENCE))
            ->willThrowException(new ProductNotFound(Factory::PRODUCT_REFERENCE));

        $projector
            ->expects(self::never())
            ->method('create');

        $handler(new ProductWasCreated(Factory::PRODUCT_REFERENCE));
    }
}
