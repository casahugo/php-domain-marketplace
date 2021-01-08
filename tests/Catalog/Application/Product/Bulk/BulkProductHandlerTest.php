<?php

declare(strict_types=1);

namespace App\Tests\Catalog\Application\Product\Bulk;

use App\Catalog\Application\Product\Bulk\BulkProductCommand;
use App\Catalog\Application\Product\Bulk\BulkProductHandler;
use App\Catalog\Application\Product\Create\CreateProductCommand;
use App\Catalog\Domain\Brand\Brand;
use App\Catalog\Domain\Brand\Id;
use App\Catalog\Domain\Category\Category;
use App\Catalog\Domain\Category\Id as CategoryId;
use App\Catalog\Domain\Company\Company;
use App\Catalog\Domain\Company\Id as CompanyId;
use App\Catalog\Domain\Product\Code;
use App\Catalog\Domain\Product\Product;
use App\Catalog\Domain\Product\ProductCollection;
use App\Catalog\Domain\Product\ProductPrice;
use App\Catalog\Domain\Product\ProductRepository;
use App\Catalog\Domain\Product\Reference;
use App\Catalog\Domain\Product\Status;
use App\Catalog\Domain\Product\Stock;
use App\Catalog\Domain\Tax\Code as TaxCode;
use App\Catalog\Domain\Tax\Tax;
use App\Catalog\Domain\Tax\TaxCollection;
use App\Catalog\Domain\Tax\TaxValue;
use App\Shared\Domain\Bus\Command\CommandBus;
use App\Shared\Domain\Email;
use App\Shared\Domain\Logger\Logger;
use App\Tests\Mock\FakeUuidGenerator;
use PHPUnit\Framework\TestCase;

final class BulkProductHandlerTest extends TestCase
{
    public function testDispatchCreateProductCommand(): void
    {
        $handler = new BulkProductHandler(
            $commandBus = $this->createMock(CommandBus::class),
            $repository = $this->createMock(ProductRepository::class),
            $logger = $this->createMock(Logger::class),
            new FakeUuidGenerator('123'),
        );

        $repository
            ->expects(self::once())
            ->method('findByCode')
            ->willReturn($this->getProductEntity());

        $commandBus
            ->expects(self::exactly(2))
            ->method('dispatch')
            ->withConsecutive(
                [
                    new CreateProductCommand(
                        '123',
                        'REF-120',
                        'Screen',
                        34,
                        4,
                        1,
                        3,
                        2,
                        ['TVA_20'],
                        [2, 3],
                        'Screen introduction',
                        'Screen description',
                        45.5,
                    ),
                ],
                [
                    new CreateProductCommand(
                        '123',
                        'REF-183',
                        'Mouse',
                        22.3,
                        4,
                        1,
                        3,
                        2,
                        ['TVA_20'],
                        [2],
                        'Mouse introduction',
                        'Mouse description',
                        32.5,
                    ),
                ]
            );

        $handler(new BulkProductCommand('1435', 2, $this->getProductLine()));
    }

    private function getProductEntity(): ProductCollection
    {
        $category = new Category(new CategoryId(3), 'Hardware');
        $brand = new Brand(new Id(1), 'Samsung');
        $tax = new Tax(new TaxCode('TVA_20'), new TaxValue(20));
        $company = new Company(new CompanyId(2), new Email('contact@email.pro'), 'Inc Corporation');

        return (new ProductCollection())->add(
            new Product(
                Reference::fromString('56'),
                new Code('REF-123'),
                'Laptop',
                new ProductPrice(120.),
                new Stock(4),
                $brand,
                $company,
                $category,
                (new TaxCollection())->add($tax),
                Status::ENABLED(),
                new \DateTimeImmutable('2020-01-01')
            ),
            new Product(
                Reference::fromString('57'),
                new Code('REF-121'),
                'Keyboard',
                new ProductPrice(30.),
                new Stock(40),
                $brand,
                $company,
                $category,
                (new TaxCollection())->add($tax),
                Status::ENABLED(),
                new \DateTimeImmutable('2020-01-01')
            )
        );
    }

    private function getProductLine(): array
    {
        return [
            [
                'code' => 'REF-123',
                'name' => 'Laptop',
                'price' => 34,
                'stock' => 4,
                'brandId' => 1,
                'categoryId' => 3,
            ],
            [
                'code' => 'REF-121',
                'name' => 'Keyboard',
                'price' => 34,
                'stock' => 4,
                'brandId' => 1,
                'categoryId' => 3,
            ],
            [
                'code' => 'REF-120',
                'name' => 'Screen',
                'price' => 34,
                'stock' => 4,
                'brandId' => 1,
                'categoryId' => 3,
                'taxes' => ['TVA_20'],
                'shippings' => [2, 3],
                'intro' => 'Screen introduction',
                'description' => 'Screen description',
                'originalPrice' => 45.5,
            ],
            [
                'code' => 'REF-183',
                'name' => 'Mouse',
                'price' => 22.3,
                'stock' => 4,
                'brandId' => 1,
                'categoryId' => 3,
                'taxes' => ['TVA_20'],
                'shippings' => [2],
                'intro' => 'Mouse introduction',
                'description' => 'Mouse description',
                'originalPrice' => 32.5,
            ],
        ];
    }
}
