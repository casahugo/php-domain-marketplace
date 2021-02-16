<?php

declare(strict_types=1);

namespace App\Tests\Catalog\Application\Product\Bulk;

use App\Catalog\Application\Product\Bulk\BulkProductCommand;
use App\Catalog\Application\Product\Bulk\BulkProductHandler;
use App\Catalog\Application\Product\Create\CreateProductCommand;
use App\Catalog\Domain\Brand\Brand;
use App\Catalog\Domain\Brand\Code as BrandCode;
use App\Catalog\Domain\Category\Category;
use App\Catalog\Domain\Category\Code as CategoryCode;
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
use App\Catalog\Domain\Tax\TaxAmount;
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
            new FakeUuidGenerator('01E439TP9XJZ9RPFH3T1PYBCR8'),
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
                        '01E439TP9XJZ9RPFH3T1PYBCR8',
                        'REF-120',
                        'Screen',
                        34,
                        4,
                        'SMSG',
                        'HRDW',
                        '01E439TP9XJZ9RPFH3T1PYBCR8',
                        ['TVA_20'],
                        'COL',
                        'Screen introduction',
                        'Screen description',
                        45.5,
                    ),
                ],
                [
                    new CreateProductCommand(
                        '01E439TP9XJZ9RPFH3T1PYBCR8',
                        'REF-183',
                        'Mouse',
                        22.3,
                        4,
                        'SMSG',
                        'HRDW',
                        '01E439TP9XJZ9RPFH3T1PYBCR8',
                        ['TVA_20'],
                        'COL',
                        'Mouse introduction',
                        'Mouse description',
                        32.5,
                    ),
                ]
            );

        $handler(new BulkProductCommand('01E439TP9XJZ9RPFH3T1PYBCR8', '01E439TP9XJZ9RPFH3T1PYBCR8', $this->getProductLine()));
    }

    private function getProductEntity(): ProductCollection
    {
        $category = new Category(new CategoryCode('HRDW'), 'Hardware');
        $brand = new Brand(new BrandCode('SMGS'), 'Samsung');
        $tax = new Tax(new TaxCode('TVA_20'), 'TVA 20%', new TaxAmount(20));
        $company = new Company(CompanyId::fromString('01E439TP9XJZ9RPFH3T1PYBCR8'), new Email('contact@email.pro'), 'Inc Corporation');

        return (new ProductCollection())->add(
            new Product(
                Reference::fromString('01E439TP9XJZ9RPFH3T1PYBCR8'),
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
                Reference::fromString('01E439TP9XJZ9RPFH3T1PYBCR8'),
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
                'brandId' => 'SMSG',
                'categoryId' => 'HRDW',
            ],
            [
                'code' => 'REF-121',
                'name' => 'Keyboard',
                'price' => 34,
                'stock' => 4,
                'brandId' => 'SMGS',
                'categoryId' => 'HRDW',
            ],
            [
                'code' => 'REF-120',
                'name' => 'Screen',
                'price' => 34,
                'stock' => 4,
                'brandId' => 'SMSG',
                'categoryId' => 'HRDW',
                'taxes' => ['TVA_20'],
                'shipping' => 'COL',
                'intro' => 'Screen introduction',
                'description' => 'Screen description',
                'originalPrice' => 45.5,
            ],
            [
                'code' => 'REF-183',
                'name' => 'Mouse',
                'price' => 22.3,
                'stock' => 4,
                'brandId' => 'SMSG',
                'categoryId' => 'HRDW',
                'taxes' => ['TVA_20'],
                'shipping' => 'COL',
                'intro' => 'Mouse introduction',
                'description' => 'Mouse description',
                'originalPrice' => 32.5,
            ],
        ];
    }
}
