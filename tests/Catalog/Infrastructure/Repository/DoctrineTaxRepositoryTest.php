<?php

declare(strict_types=1);

namespace App\Tests\Catalog\Infrastructure\Repository;

use App\Catalog\Domain\Tax\Code;
use App\Catalog\Domain\Tax\TaxCollection;
use App\Catalog\Infrastructure\Doctrine\DoctrineTaxRepository;
use PHPUnit\Framework\TestCase;

final class DoctrineTaxRepositoryTest extends TestCase
{
    public function testFindByCode(): void
    {
        $repository = new DoctrineTaxRepository();

        $taxes = $repository->findByCode(new Code('TVA_20'));

        self::assertInstanceOf(TaxCollection::class, $taxes);
    }
}
