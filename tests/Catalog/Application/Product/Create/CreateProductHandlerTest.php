<?php

declare(strict_types=1);

namespace App\Tests\Catalog\Application\Product\Create;

use App\Tests\Mock\FrozenClock;
use App\Catalog\Application\{
    Product\Create\CreateProductCommand,
    Product\Create\CreateProductHandler
};
use App\Catalog\Domain\{
    Brand\Brand,
    Brand\BrandRepository,
    Brand\Id as BrandId,
    Category\Category,
    Category\CategoryRepository,
    Category\Id,
    Product\Code,
    Product\Product,
    Product\ProductPrice,
    Product\ProductRepository,
    Product\Reference,
    Product\Status,
    Product\Stock,
    Seller\Id as SellerId,
    Seller\Seller,
    Seller\SellerRepository,
    Tax\TaxCollection};
use App\Shared\{
    Domain\Bus\Event\EventBus,
    Domain\Email,
    Domain\Event\Product\ProductWasCreated,
    Infrastructure\Uuid\Uuid};
use PHPUnit\Framework\TestCase;

final class CreateProductHandlerTest extends TestCase
{
    public function testCreateProduct(): void
    {
        $handler = new CreateProductHandler(
            $eventBus = $this->createMock(EventBus::class),
            $productRepository = $this->createMock(ProductRepository::class),
            $categoryRepository = $this->createMock(CategoryRepository::class),
            $brandRepository = $this->createMock(BrandRepository::class),
            $sellerRepository = $this->createMock(SellerRepository::class),
            new FrozenClock(new \DateTimeImmutable("2020-01-01"))
        );

        $categoryRepository
            ->expects(self::once())
            ->method('get')
            ->with($categoryId = new Id(2))
            ->willReturn($category = new Category($categoryId, 'Computer'));

        $sellerRepository
            ->expects(self::once())
            ->method('get')
            ->with($sellerId = new SellerId(123))
            ->willReturn($seller = new Seller($sellerId, new Email('company@tld.com'), 'Inc Corporation'));

        $brandRepository
            ->expects(self::once())
            ->method('get')
            ->with($brandId = new BrandId(34))
            ->willReturn($brand = new Brand($brandId, 'Toshiba'));

        $product = new Product(
            new Reference($uuid = new Uuid('123')),
            new Code('code'),
            'Laptop',
            new ProductPrice(12.1),
            new Stock(2),
            $brand,
            $seller,
            $category,
            new TaxCollection(),
            Status::WAIT_MODERATION(),
            new \DateTimeImmutable("2020-01-01"),
        );

        $product->record(new ProductWasCreated($uuid));

        $productRepository
            ->expects(self::once())
            ->method('save')
            ->with();

        $handler(new CreateProductCommand(
            new Uuid('123'),
            'code',
            'Laptop',
            12.1,
            2,
            34,
            2,
            123,
            4,
        ));
    }
}
