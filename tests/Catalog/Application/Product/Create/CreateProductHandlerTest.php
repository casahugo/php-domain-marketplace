<?php

declare(strict_types=1);

namespace App\Tests\Catalog\Application\Product\Create;

use App\Tests\Catalog\Factory;
use App\Tests\Mock\FrozenClock;
use App\Catalog\Application\{
    Product\Create\CreateProductCommand,
    Product\Create\CreateProductHandler
};
use App\Catalog\Domain\{
    Brand\BrandRepository,
    Category\CategoryRepository,
    Product\ProductRepository,
    Company\CompanyRepository,
    Shipping\ShippingRepository,
    Tax\TaxCollection,
    Tax\TaxRepository};
use App\Shared\{
    Domain\Bus\Event\EventBus,
    Domain\Event\Product\ProductWasCreated,
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
            $brandRepository = $this->createMock(BrandRepository::class),
            $sellerRepository = $this->createMock(CompanyRepository::class),
            $taxRepository = $this->createMock(TaxRepository::class),
            $shippingRepository = $this->createMock(ShippingRepository::class),
            new FrozenClock(new \DateTimeImmutable("2020-01-01"))
        );

        $categoryRepository
            ->expects(self::once())
            ->method('get')
            ->with(Factory::getCategoryCode())
            ->willReturn(Factory::getCategory());

        $sellerRepository
            ->expects(self::once())
            ->method('get')
            ->with(Factory::getCompanyId())
            ->willReturn(Factory::getCompany());

        $brandRepository
            ->expects(self::once())
            ->method('get')
            ->with(Factory::getBrandCode())
            ->willReturn(Factory::getBrand());

        $taxRepository
            ->expects(self::once())
            ->method('findByCode')
            ->willReturn(new TaxCollection());

        $shippingRepository
            ->expects(self::once())
            ->method('get')
            ->with(Factory::getShippingCode())
            ->willReturn(Factory::getShipping());

        $product = Factory::getProduct();

        $product->record(new ProductWasCreated(Factory::PRODUCT_REFERENCE));

        $productRepository
            ->expects(self::once())
            ->method('save')
            ->with();

        $handler(new CreateProductCommand(
            Factory::PRODUCT_REFERENCE,
            Factory::PRODUCT_CODE,
            Factory::PRODUCT_NAME,
            Factory::PRODUCT_PRICE,
            Factory::PRODUCT_STOCK,
            Factory::BRAND_CODE,
            Factory::CATEGORY_CODE,
            Factory::COMPANY_ID,
            ['TVA_20'],
            'UPS',
        ));
    }
}
