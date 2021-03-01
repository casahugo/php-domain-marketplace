<?php

declare(strict_types=1);

namespace App\Tests\Catalog\Application\Import\Create;

use App\Catalog\Application\Import\Create\CreateImportProductCommand;
use App\Catalog\Application\Import\Create\CreateImportProductHandler;
use App\Catalog\Application\Product\Bulk\BulkProductCommand;
use App\Catalog\Domain\Import\BulkCollection;
use App\Catalog\Domain\Import\Import;
use App\Catalog\Domain\Import\ImportReader;
use App\Catalog\Domain\Import\ImportRepository;
use App\Shared\Domain\Bus\Command\CommandBus;
use App\Shared\Domain\Storage\FileStorage;
use PHPUnit\Framework\TestCase;

final class CreateImportProductHandlerTest extends TestCase
{
    public function testCreateCommand(): void
    {
        $handler = new CreateImportProductHandler(
            $commandBus = $this->createMock(CommandBus::class),
            $repository = $this->createMock(ImportRepository::class),
            $reader = $this->createMock(ImportReader::class),
            $storage = $this->createMock(FileStorage::class),
        );

        $products = $this->getProductLine();

        $storage
            ->expects(self::once())
            ->method('copy')
            ->with('/tmp/product.csv', 'product.csv');

        $repository
            ->expects(self::once())
            ->method('save')
            ->with(Import::create('01E439TP9XJZ9RPFH3T1PYBCR8', '/tmp/product.csv'));

        $reader
            ->expects(self::once())
            ->method('read')
            ->with('/tmp/product.csv')
            ->willReturn(new BulkCollection($products));

        $commandBus
            ->expects(self::exactly(2))
            ->method('dispatch')
            ->withConsecutive(
                [new BulkProductCommand(
                    '01E439TP9XJZ9RPFH3T1PYBCR8',
                    '01E439TP9XJZ9RPFH3T1PYBCR8',
                    array_slice($products, 0, 5)
                )],
                [new BulkProductCommand(
                    '01E439TP9XJZ9RPFH3T1PYBCR8',
                    '01E439TP9XJZ9RPFH3T1PYBCR8',
                    array_slice($products, 5)
                )],
            );

        $handler(new CreateImportProductCommand('01E439TP9XJZ9RPFH3T1PYBCR8', '01E439TP9XJZ9RPFH3T1PYBCR8', '/tmp/product.csv'));
    }

    private function getProductLine(): array
    {
        return [
            [
                'code' => 'REF-123',
                'name' => 'Laptop',
            ],
            [
                'code' => 'REF-121',
                'name' => 'Keyboard',
            ],
            [
                'code' => 'REF-120',
                'name' => 'Screen',
            ],
            [
                'code' => 'REF-183',
                'name' => 'Mouse',
            ],
            [
                'code' => 'REF-423',
                'name' => 'Head set',
            ],
            [
                'code' => 'REF-10',
                'name' => 'Invoice',
            ],
            [
                'code' => 'REF-323',
                'name' => 'Desk',
            ],
            [
                'code' => 'REF-523',
                'name' => 'Light',
            ],
        ];
    }
}
