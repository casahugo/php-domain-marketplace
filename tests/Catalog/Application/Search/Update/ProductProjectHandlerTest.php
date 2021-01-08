<?php

declare(strict_types=1);

namespace App\Tests\Catalog\Application\Search\Update;

use App\Catalog\Application\Search\Update\ProductProjectorHandler;
use App\Catalog\Domain\Brand\Brand;
use App\Catalog\Domain\Brand\Id;
use App\Catalog\Domain\Category\Category;
use App\Catalog\Domain\Category\Id as CategoryId;
use App\Catalog\Domain\Company\Company;
use App\Catalog\Domain\Company\Id as CompanyId;
use App\Catalog\Domain\Product\Code;
use App\Catalog\Domain\Product\Product;
use App\Catalog\Domain\Product\ProductPrice;
use App\Catalog\Domain\Product\ProductProjector;
use App\Catalog\Domain\Product\ProductRepository;
use App\Catalog\Domain\Product\Reference;
use App\Catalog\Domain\Product\Status;
use App\Catalog\Domain\Product\Stock;
use App\Catalog\Domain\Tax\TaxCollection;
use App\Shared\Domain\Email;
use App\Shared\Domain\Event\Product\ProductHasChanged;
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
            ->with($reference = Reference::fromString('123'))
            ->willReturn($product = new Product(
                $reference,
                new Code('345'),
                'Laptop',
                new ProductPrice(12.2),
                new Stock(34),
                new Brand(new Id(1), 'Samsung'),
                new Company(new CompanyId(2), new Email('contact@email.com'), 'Inc Coporation'),
                new Category(new CategoryId(2), 'Hardware'),
                new TaxCollection(),
                Status::ENABLED(),
                new \DateTimeImmutable()
            ));

        $projector
            ->expects(self::once())
            ->method('push')
            ->with($product);

        $projector
            ->expects(self::never())
            ->method('delete');

        $handler(new ProductHasChanged('123'));
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
            ->with($reference = Reference::fromString('123'))
            ->willReturn($product = new Product(
                $reference,
                new Code('345'),
                'Laptop',
                new ProductPrice(12.2),
                new Stock(34),
                new Brand(new Id(1), 'Samsung'),
                new Company(new CompanyId(2), new Email('contact@email.com'), 'Inc Coporation'),
                new Category(new CategoryId(2), 'Hardware'),
                new TaxCollection(),
                Status::DISABLED(),
                new \DateTimeImmutable()
            ));

        $projector
            ->expects(self::never())
            ->method('push');

        $projector
            ->expects(self::once())
            ->method('delete')
            ->with($product);

        $handler(new ProductHasChanged('123'));
    }
}
