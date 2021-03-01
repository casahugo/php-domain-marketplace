<?php

declare(strict_types=1);

namespace App\Tests\Catalog\Infrastructure\Repository;

use App\Catalog\Domain\Tax\Code;
use App\Catalog\Domain\Tax\TaxCollection;
use App\Catalog\Infrastructure\Doctrine\DoctrineTaxRepository;
use Doctrine\DBAL\Connection;
use PHPUnit\Framework\TestCase;

final class DoctrineTaxRepositoryTest extends TestCase
{
    public function testFindByCode(): void
    {
        $repository = new DoctrineTaxRepository(
            $connection = $this->createMock(Connection::class)
        );

        $taxes = $repository->findByCodes(new Code('TVA_20'));

        self::assertInstanceOf(TaxCollection::class, $taxes);
    }
}
