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
    Brand\Code as BrandCode,
    Category\Category,
    Category\CategoryRepository,
    Category\Code as CategoryCode,
    Product\Code,
    Product\Product,
    Product\ProductPrice,
    Product\ProductRepository,
    Product\Reference,
    Product\Status,
    Product\Stock,
    Company\Id as CompanyId,
    Company\Company,
    Company\CompanyRepository,
    Tax\TaxCollection,
    Tax\TaxRepository
};
use App\Shared\{
    Domain\Bus\Event\EventBus,
    Domain\Email,
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
            new FrozenClock(new \DateTimeImmutable("2020-01-01"))
        );

        $categoryRepository
            ->expects(self::once())
            ->method('get')
            ->with($categoryId = new CategoryCode("HRDW"))
            ->willReturn($category = new Category($categoryId, 'Computer'));

        $sellerRepository
            ->expects(self::once())
            ->method('get')
            ->with($companyId = CompanyId::fromString('01E439TP9XJZ9RPFH3T1PYBCR8'))
            ->willReturn($company = new Company($companyId, new Email('company@tld.com'), 'Inc Corporation'));

        $brandRepository
            ->expects(self::once())
            ->method('get')
            ->with($brandId = new BrandCode("SMGS"))
            ->willReturn($brand = new Brand($brandId, 'Samsung'));

        $taxRepository
            ->expects(self::once())
            ->method('findByCode')
            ->willReturn(new TaxCollection());

        $product = new Product(
            Reference::fromString('01E439TP9XJZ9RPFH3T1PYBCR8'),
            new Code('code'),
            'Laptop',
            new ProductPrice(12.1),
            new Stock(2),
            $brand,
            $company,
            $category,
            new TaxCollection(),
            Status::WAIT_MODERATION(),
            new \DateTimeImmutable("2020-01-01"),
        );

        $product->record(new ProductWasCreated('01E439TP9XJZ9RPFH3T1PYBCR8'));

        $productRepository
            ->expects(self::once())
            ->method('save')
            ->with();

        $handler(new CreateProductCommand(
            '01E439TP9XJZ9RPFH3T1PYBCR8',
            'code',
            'Laptop',
            12.1,
            2,
            'SMGS',
            'HRDW',
            '01E439TP9XJZ9RPFH3T1PYBCR8',
            ['TVA_20'],
            [4],
        ));
    }
}
