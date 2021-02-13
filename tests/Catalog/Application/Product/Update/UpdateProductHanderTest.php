<?php

declare(strict_types=1);

namespace App\Tests\Catalog\Application\Product\Update;

use App\Catalog\Application\Product\Update\UpdateProductCommand;
use App\Catalog\Application\Product\Update\UpdateProductHandler;
use App\Catalog\Domain\Category\CategoryRepository;
use App\Catalog\Domain\Category\Code;
use App\Catalog\Domain\Exception\ProductSaveFailedException;
use App\Catalog\Domain\Product\ProductRepository;
use App\Catalog\Domain\Product\Reference;
use App\Shared\Domain\Bus\Event\EventBus;
use App\Shared\Domain\Event\Product\ProductHasChanged;
use App\Shared\Domain\Event\Product\ProductPriceHasChanged;
use App\Shared\Domain\Event\Product\ProductStockHasChanged;
use App\Tests\Catalog\Factory;
use PHPUnit\Framework\TestCase;

final class UpdateProductHanderTest extends TestCase
{
    public function testUpdate(): void
    {
        $handler = new UpdateProductHandler(
            $eventBus = $this->createMock(EventBus::class),
            $productRepository = $this->createMock(ProductRepository::class),
            $categoryRepository = $this->createMock(CategoryRepository::class),
        );

        $productRepository
            ->expects(self::once())
            ->method('get')
            ->with(Reference::fromString(Factory::PRODUCT_REFERENCE))
            ->willReturn($product = Factory::getProduct());

        $categoryRepository
            ->expects(self::once())
            ->method('get')
            ->with(new Code('HRDW'))
            ->willReturn(Factory::getCategory('HRDW', 'Hardware'));

        $productRepository
            ->expects(self::once())
            ->method('save')
            ->with($product);

        $eventBus
            ->expects(self::once())
            ->method('dispatch')
            ->with(
                new ProductPriceHasChanged(Factory::PRODUCT_REFERENCE, 56.45, 56.45),
                new ProductStockHasChanged(Factory::PRODUCT_REFERENCE, 200),
                new ProductHasChanged(Factory::PRODUCT_REFERENCE)
            );

        $handler(new UpdateProductCommand(
            Factory::PRODUCT_REFERENCE,
            'Mouse',
            56.45,
            200,
            'HRDW'
        ));
    }

    public function testUpdateFailed(): void
    {
        $this->expectException(ProductSaveFailedException::class);
        $this->expectExceptionMessage('Failed save product #01E439TP9XJZ9RPFH3T1PYBCR8');
        $this->expectExceptionCode(500);

        $handler = new UpdateProductHandler(
            $eventBus = $this->createMock(EventBus::class),
            $productRepository = $this->createMock(ProductRepository::class),
            $categoryRepository = $this->createMock(CategoryRepository::class),
        );

        $productRepository
            ->expects(self::once())
            ->method('get')
            ->with(Reference::fromString(Factory::PRODUCT_REFERENCE))
            ->willReturn($product = Factory::getProduct());

        $categoryRepository
            ->expects(self::once())
            ->method('get')
            ->with(new Code('HRDW'))
            ->willReturn(Factory::getCategory('HRDW', 'Hardware'));

        $productRepository
            ->expects(self::once())
            ->method('save')
            ->with($product)
            ->willThrowException(new ProductSaveFailedException(Factory::PRODUCT_REFERENCE));

        $eventBus
            ->expects(self::never())
            ->method('dispatch');

        $handler(new UpdateProductCommand(
            Factory::PRODUCT_REFERENCE,
            'Mouse',
            56.45,
            200,
            'HRDW'
        ));
    }
}
