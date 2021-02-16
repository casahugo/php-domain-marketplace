<?php

declare(strict_types=1);

namespace App\Tests\Catalog\Infrastructure\Http\Product;

use App\Catalog\Application\Product\Create\CreateProductCommand;
use App\Catalog\Infrastructure\Http\Product\CreateProductController;
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
            $generator = new FakeUuidGenerator('01E439TP9XJZ9RPFH3T1PYBCR8')
        );

        $reference = (string) $generator->generate();

        $request = new Request([], [
            'code' => 'RZE-OO1',
            'name' => 'Laptop',
            'price' => 12.2,
            'stock' => 12,
            'brandCode' => 'SMSG',
            'categoryCode' => 'HRDW',
            'companyId' => '01E439TP9XJZ9RPFH3T1PYBCR8',
            'taxes' => ['TVA_20'],
            'shipping' => 'UPS',
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
                'SMSG',
                'HRDW',
                '01E439TP9XJZ9RPFH3T1PYBCR8',
                ['TVA_20'],
                'UPS',
            ));

        $response = $controller($request);

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertSame(['reference' => $reference], json_decode($response->getContent(), true));
    }

    public function testBadArguments(): void
    {
        $controller = new CreateProductController(
            $commandBus = $this->createMock(CommandBus::class),
            $generator = new FakeUuidGenerator('01E439TP9XJZ9RPFH3T1PYBCR8')
        );

        $request = new Request([], [
            'code' => 'RZE-OO1',
            'name' => 'Laptop',
            'price' => 12.2,
        ]);

        $response = $controller($request);

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertSame(400, $response->getStatusCode());
    }
}
