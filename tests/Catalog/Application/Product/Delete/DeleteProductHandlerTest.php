<?php

declare(strict_types=1);

namespace App\Tests\Catalog\Application\Product\Delete;

use App\Catalog\Application\Product\Delete\DeleteProductCommand;
use App\Catalog\Application\Product\Delete\DeleteProductHandler;
use App\Catalog\Domain\Exception\ProductDeleteFailedException;
use App\Catalog\Domain\Product\ProductRepository;
use App\Catalog\Domain\Product\Reference;
use App\Shared\Domain\Bus\Event\EventBus;
use App\Shared\Domain\Event\Product\ProductWasDeleted;
use App\Tests\Catalog\Factory;
use PHPUnit\Framework\TestCase;

final class DeleteProductHandlerTest extends TestCase
{
    public function testDelete(): void
    {
        $handler = new DeleteProductHandler(
            $eventBus = $this->createMock(EventBus::class),
            $productRepository = $this->createMock(ProductRepository::class),
        );

        $productRepository
            ->expects(self::once())
            ->method('get')
            ->with(Reference::fromString(Factory::PRODUCT_REFERENCE))
            ->willReturn($product = Factory::getProduct(Factory::PRODUCT_REFERENCE))
        ;

        $productRepository
            ->expects(self::once())
            ->method('delete')
            ->with($product)
        ;

        $eventBus
            ->expects(self::once())
            ->method('dispatch')
            ->with(new ProductWasDeleted(Factory::PRODUCT_REFERENCE))
        ;

        $handler(new DeleteProductCommand(Factory::PRODUCT_REFERENCE));
    }

    public function testDeleteFailed(): void
    {
        $this->expectException(ProductDeleteFailedException::class);
        $this->expectExceptionMessage('Failed delete product #01E439TP9XJZ9RPFH3T1PYBCR8');
        $this->expectExceptionCode(500);

        $handler = new DeleteProductHandler(
            $eventBus = $this->createMock(EventBus::class),
            $productRepository = $this->createMock(ProductRepository::class),
        );

        $productRepository
            ->expects(self::once())
            ->method('get')
            ->with(Reference::fromString(Factory::PRODUCT_REFERENCE))
            ->willReturn($product = Factory::getProduct(Factory::PRODUCT_REFERENCE))
        ;

        $productRepository
            ->expects(self::once())
            ->method('delete')
            ->with($product)
            ->willThrowException(new ProductDeleteFailedException(Factory::PRODUCT_REFERENCE))
        ;

        $eventBus
            ->expects(self::never())
            ->method('dispatch')
            ->with(new ProductWasDeleted(Factory::PRODUCT_REFERENCE))
        ;

        $handler(new DeleteProductCommand(Factory::PRODUCT_REFERENCE));
    }
}
