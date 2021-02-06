<?php

declare(strict_types=1);

namespace App\Catalog\Infrastructure\TypeSense;

use App\Catalog\Domain\Product\Product;
use App\Catalog\Domain\Product\ProductCollection;
use App\Catalog\Domain\Product\ProductRepository;
use App\Catalog\Domain\Product\Reference;
use Typesense\Client;

final class TypeSenseProductRepository implements ProductRepository
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'api_key'         => 'abc',
            'nodes'           => [
                [
                    'host'     => 'localhost',
                    'port'     => '8108',
                    'protocol' => 'http',
                ],
            ],
            'connection_timeout_seconds' => 2,
        ]);
    }

    public function get(Reference $reference): Product
    {
        // TODO: Implement get() method.
    }

    public function delete(Product $product): void
    {
        // TODO: Implement delete() method.
    }

    public function save(Product $product): void
    {
    }

    public function list(int $limit, int $offset, array $filters, array $orders = []): ProductCollection
    {
        // TODO: Implement list() method.
    }
}
