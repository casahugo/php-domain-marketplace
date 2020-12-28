<?php

declare(strict_types=1);

namespace App\Tests\Catalog\Infrastructure\Controller\Product;

use App\Catalog\Application\Product\Create\CreateProductCommand;
use App\Catalog\Infrastructure\Controller\Product\CreateProductController;
use App\Shared\Domain\Bus\Command\CommandBus;
use App\Tests\Mock\FakeUuidGenerator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final class CreateProductControllerTest extends TestCase
{
    public function testCreateProduct(): void
    {
        $controller = new CreateProductController(
            $commandBus = $this->createMock(CommandBus::class),
            $generator = new FakeUuidGenerator('uuid-123')
        );

        $reference = $generator->generate();

        $request = new Request([], [
            'code' => 'RZE-OO1',
            'name' => 'Laptop',
            'price' => 12.2,
            'stock' => 12,
            'categoryId' => 2,
            'sellerId' => 12,
        ]);

        $commandBus
            ->expects(self::once())
            ->method('dispatch')
            ->with(new CreateProductCommand(
                $reference,
                'RZE-OO1',
                'Laptop',
                12.2,
                12,
                2,
                12,
            ));

        $response = $controller($request);

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertSame(['reference' => (string) $reference], json_decode($response->getContent(), true));
    }
}
