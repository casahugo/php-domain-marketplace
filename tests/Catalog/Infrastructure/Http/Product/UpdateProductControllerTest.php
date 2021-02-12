<?php

declare(strict_types=1);

namespace App\Tests\Catalog\Infrastructure\Http\Product;

use App\Catalog\Application\Product\Update\UpdateProductCommand;
use App\Catalog\Infrastructure\Http\Product\UpdateProductController;
use App\Shared\Domain\Bus\Command\CommandBus;
use App\Tests\Catalog\Factory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final class UpdateProductControllerTest extends TestCase
{
    public function testUpdate(): void
    {
        $controller = new UpdateProductController(
            $commandBus = $this->createMock(CommandBus::class),
        );

        $request = new Request([], [
            'name' => 'Laptop',
            'price' => 12.2,
            'stock' => 12,
            'categoryCode' => 'HRDW',
        ]);

        $commandBus
            ->expects(self::once())
            ->method('dispatch')
            ->with(new UpdateProductCommand(
                Factory::PRODUCT_REFERENCE,
                'Laptop',
                12.2,
                12,
                'HRDW',
            ));

        $response = $controller(Factory::PRODUCT_REFERENCE, $request);

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertSame(200, $response->getStatusCode());
        self::assertSame([], json_decode($response->getContent(), true));
    }

    public function testBadArguments(): void
    {
        $controller = new UpdateProductController(
            $commandBus = $this->createMock(CommandBus::class),
        );

        $request = new Request([], [
            'name' => 'Laptop',
            'price' => 12.2,
            'stock' => '12', // must be float
            'categoryCode' => 'HRDW',
        ]);

        $commandBus
            ->expects(self::never())
            ->method('dispatch');

        $response = $controller(Factory::PRODUCT_REFERENCE, $request);

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertSame(400, $response->getStatusCode());
        self::assertSame(
            ['message' => 'The option "stock" with value "12" is expected to be of type "int", but is of type "string".'],
            json_decode($response->getContent(), true)
        );
    }
}
