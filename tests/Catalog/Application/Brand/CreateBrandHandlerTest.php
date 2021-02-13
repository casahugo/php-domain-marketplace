<?php

declare(strict_types=1);

namespace App\Tests\Catalog\Application\Brand;

use App\Catalog\Application\Brand\Create\CreateBrandCommand;
use App\Catalog\Application\Brand\Create\CreateBrandHandler;
use App\Catalog\Domain\Brand\Brand;
use App\Catalog\Domain\Brand\BrandRepository;
use App\Catalog\Domain\Brand\Code;
use App\Catalog\Domain\Exception\BrandSaveFailedException;
use PHPUnit\Framework\TestCase;

final class CreateBrandHandlerTest extends TestCase
{
    public function testCreate(): void
    {
        $handler = new CreateBrandHandler($repository = $this->createMock(BrandRepository::class));

        $repository
            ->expects(self::once())
            ->method('save')
            ->with(new Brand(new Code('SMSG'), 'Samsung'));

        $handler(new CreateBrandCommand('SMSG', 'Samsung'));
    }

    public function testSaveFailed(): void
    {
        $this->expectException(BrandSaveFailedException::class);
        $this->expectExceptionMessage('Failed save brand #SMSG');
        $this->expectExceptionCode(500);

        $handler = new CreateBrandHandler($repository = $this->createMock(BrandRepository::class));

        $repository
            ->expects(self::once())
            ->method('save')
            ->with(new Brand(new Code('SMSG'), 'Samsung'))
            ->willThrowException(new BrandSaveFailedException("SMSG"))
        ;

        $handler(new CreateBrandCommand('SMSG', 'Samsung'));
    }
}
