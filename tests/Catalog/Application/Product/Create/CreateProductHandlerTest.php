<?php

declare(strict_types=1);

namespace App\Tests\Catalog\Application\Product\Create;

use App\Catalog\Application\{
    Product\Create\CreateProductCommand,
    Product\Create\CreateProductHandler
};
use App\Catalog\Domain\{
    Category\Category,
    Category\CategoryRepository,
    Category\Id,
    Product\ProductRepository,
    Seller\Id as SellerId,
    Seller\Seller,
    Seller\SellerRepository
};
use App\Shared\{
    Domain\Bus\Event\EventBus,
    Domain\Email,
    Infrastructure\Uuid\Uuid
};
use PHPUnit\Framework\TestCase;

final class CreateProductHandlerTest extends TestCase
{
    public function testCreateProduct(): void
    {
        $handler = new CreateProductHandler(
            $eventBus = $this->createMock(EventBus::class),
            $productRepository = $this->createMock(ProductRepository::class),
            $categoryRepository = $this->createMock(CategoryRepository::class),
            $sellerRepository = $this->createMock(SellerRepository::class),
        );

        $categoryRepository
            ->expects(self::once())
            ->method('get')
            ->with($categoryId = new Id(2))
            ->willReturn(new Category($categoryId, 'Computer'));

        $sellerRepository
            ->expects(self::once())
            ->method('get')
            ->with($sellerId = new SellerId(123))
            ->willReturn(new Seller($sellerId, new Email('company@tld.com'), 'Inc Corporation'));

        $productRepository
            ->expects(self::once())
            ->method('save');

        $command = new CreateProductCommand(
            new Uuid('123'),
            'code',
            'Laptop',
            12.1,
            2,
            2,
            123
        );

        $handler($command);
    }
}
