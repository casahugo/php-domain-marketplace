<?php

declare(strict_types=1);

namespace App\Tests\Catalog\Application\Search\Update;

use App\Catalog\{
    Application\Search\Update\ProductProjectorHandler,
    Domain\Brand\Brand,
    Domain\Brand\Code as BrandCode,
    Domain\Category\Category,
    Domain\Category\Code as CategoryCode,
    Domain\Company\Company,
    Domain\Company\Id as CompanyId,
    Domain\Product\Code,
    Domain\Product\Product,
    Domain\Product\ProductPrice,
    Domain\Product\ProductProjector,
    Domain\Product\ProductRepository,
    Domain\Product\Reference,
    Domain\Product\Status,
    Domain\Product\Stock,
    Domain\Tax\TaxCollection
};
use App\Shared\{
    Domain\Email,
    Domain\Event\Product\ProductHasChanged
};
use PHPUnit\Framework\TestCase;

final class ProductProjectHandlerTest extends TestCase
{
    public function testPushProductIfEnable(): void
    {
        $handler = new ProductProjectorHandler(
            $repository = $this->createMock(ProductRepository::class),
            $projector = $this->createMock(ProductProjector::class)
        );

        $repository
            ->expects(self::once())
            ->method('get')
            ->with($reference = Reference::fromString('01E439TP9XJZ9RPFH3T1PYBCR8'))
            ->willReturn($product = new Product(
                $reference,
                new Code('345'),
                'Laptop',
                new ProductPrice(12.2),
                new Stock(34),
                new Brand(new BrandCode('SMSG'), 'Samsung'),
                new Company(CompanyId::fromString('01E439TP9XJZ9RPFH3T1PYBCR8'), new Email('contact@email.com'), 'Inc Coporation'),
                new Category(new CategoryCode('HRDW'), 'Hardware'),
                new TaxCollection(),
                Status::ENABLED(),
                new \DateTimeImmutable()
            ));

        $projector
            ->expects(self::once())
            ->method('create')
            ->with($product);

        $projector
            ->expects(self::never())
            ->method('delete');

        $handler(new ProductHasChanged('01E439TP9XJZ9RPFH3T1PYBCR8'));
    }

    public function testDeleteProduct(): void
    {
        $handler = new ProductProjectorHandler(
            $repository = $this->createMock(ProductRepository::class),
            $projector = $this->createMock(ProductProjector::class)
        );

        $repository
            ->expects(self::once())
            ->method('get')
            ->with($reference = Reference::fromString('01E439TP9XJZ9RPFH3T1PYBCR8'))
            ->willReturn($product = new Product(
                $reference,
                new Code('345'),
                'Laptop',
                new ProductPrice(12.2),
                new Stock(34),
                new Brand(new BrandCode('SMSG'), 'Samsung'),
                new Company(CompanyId::fromString('01E439TP9XJZ9RPFH3T1PYBCR8'), new Email('contact@email.com'), 'Inc Coporation'),
                new Category(new CategoryCode('HDRW'), 'Hardware'),
                new TaxCollection(),
                Status::DISABLED(),
                new \DateTimeImmutable()
            ));

        $projector
            ->expects(self::never())
            ->method('create');

        $projector
            ->expects(self::once())
            ->method('delete')
            ->with($product);

        $handler(new ProductHasChanged('01E439TP9XJZ9RPFH3T1PYBCR8'));
    }
}
