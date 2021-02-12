<?php

declare(strict_types=1);

namespace App\Tests\Catalog\Infrastructure\Http\Product;

use App\Catalog\Application\Product\Find\FindProduct;
use App\Catalog\Domain\Exception\ProductNotFound;
use App\Catalog\Domain\Product\ProductRepository;
use App\Catalog\Domain\Product\Reference;
use App\Catalog\Infrastructure\Http\Product\FindProductController;
use App\Tests\Catalog\Factory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class FindProductControllerTest extends TestCase
{
    public function testFind(): void
    {
        $controller = new FindProductController(
            new FindProduct($repository = $this->createMock(ProductRepository::class)),
            $normalizer = $this->createMock(NormalizerInterface::class)
        );

        $repository
            ->expects(self::once())
            ->method('get')
            ->with(Reference::fromString(Factory::PRODUCT_REFERENCE))
            ->willReturn($product = Factory::getProduct(Factory::PRODUCT_REFERENCE));

        $normalizer
            ->expects(self::once())
            ->method('normalize')
            ->with($product);

        $response = $controller(Factory::PRODUCT_REFERENCE);

        self::assertSame(200, $response->getStatusCode());
    }

    public function testNotFound(): void
    {
        $controller = new FindProductController(
            new FindProduct($repository = $this->createMock(ProductRepository::class)),
            $normalizer = $this->createMock(NormalizerInterface::class)
        );

        $repository
            ->expects(self::once())
            ->method('get')
            ->with(Reference::fromString(Factory::PRODUCT_REFERENCE))
            ->willThrowException(new ProductNotFound(Factory::PRODUCT_REFERENCE));

        $normalizer
            ->expects(self::never())
            ->method('normalize');

        $response = $controller(Factory::PRODUCT_REFERENCE);

        self::assertSame(404, $response->getStatusCode());
        self::assertSame(
            ['message' => 'Product #01E439TP9XJZ9RPFH3T1PYBCR8 not found'],
            json_decode($response->getContent(), true)
        );
    }
}
