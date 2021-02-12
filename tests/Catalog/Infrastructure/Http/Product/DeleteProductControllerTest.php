<?php

declare(strict_types=1);

namespace App\Tests\Catalog\Infrastructure\Http\Product;

use App\Catalog\Application\Product\Delete\DeleteProductCommand;
use App\Catalog\Infrastructure\Http\Product\DeleteProductController;
use App\Shared\Domain\Bus\Command\CommandBus;
use App\Tests\Catalog\Factory;
use PHPUnit\Framework\TestCase;

final class DeleteProductControllerTest extends TestCase
{
    public function testDelete(): void
    {
        $controller = new DeleteProductController(
            $commandBus = $this->createMock(CommandBus::class)
        );

        $commandBus
            ->expects(self::once())
            ->method('dispatch')
            ->with(new DeleteProductCommand(Factory::PRODUCT_REFERENCE));

        $response = $controller(Factory::PRODUCT_REFERENCE);

        self::assertSame(204, $response->getStatusCode());
    }
}
