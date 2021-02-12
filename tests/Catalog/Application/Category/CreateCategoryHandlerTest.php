<?php

declare(strict_types=1);

namespace App\Tests\Catalog\Application\Category;

use App\Catalog\Application\Category\Create\CreateCategoryCommand;
use App\Catalog\Application\Category\Create\CreateCategoryHandler;
use App\Catalog\Domain\Category\Category;
use App\Catalog\Domain\Category\CategoryRepository;
use App\Catalog\Domain\Category\Code;
use App\Catalog\Domain\Exception\CategorySaveFailedException;
use PHPUnit\Framework\TestCase;

final class CreateCategoryHandlerTest extends TestCase
{
    public function testCreate(): void
    {
        $handler = new CreateCategoryHandler(
            $repository = $this->createMock(CategoryRepository::class)
        );

        $repository
            ->expects(self::once())
            ->method('save')
            ->with(new Category(new Code('HRDW'), 'Hardware'));

        $handler(new CreateCategoryCommand('HRDW', 'Hardware'));
    }

    public function testSaveFailed(): void
    {
        $this->expectException(CategorySaveFailedException::class);
        $this->expectExceptionMessage('Failed save category #HRDW');
        $this->expectExceptionCode(500);

        $handler = new CreateCategoryHandler(
            $repository = $this->createMock(CategoryRepository::class)
        );

        $repository
            ->expects(self::once())
            ->method('save')
            ->with(new Category(new Code('HRDW'), 'Hardware'))
            ->willThrowException(new CategorySaveFailedException("HRDW"))
        ;

        $handler(new CreateCategoryCommand('HRDW', 'Hardware'));
    }
}
