<?php

declare(strict_types=1);

namespace App\Catalog\Infrastructure\TypeSense;

use App\Catalog\Domain\Product\Product;
use App\Catalog\Domain\Product\ProductProjector;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Typesense\Client;

final class TypeSenseProductProjector implements ProductProjector
{
    public const SORT = 'sortedPrice';

    public function __construct(private Client $client, private NormalizerInterface $serializer)
    {
    }

    public function configure(): void
    {
        $this->client->collections->create([
            'name'      => 'product',
            'num_documents'=> 0,
            'fields'    => [
                [
                    'name'  => 'reference',
                    'type'  => 'string',
                ],
                [
                    'name'  => 'code',
                    'type'  => 'string',
                ],
                [
                    'name'  => 'name',
                    'type'  => 'string',
                ],
                [
                    'name'  => 'sortedPrice',
                    'type'  => 'float',
                    'facet' => true,
                ],
            ],
            'default_sorting_field' => self::SORT,
        ]);
    }

    public function create(Product $product): void
    {
        $this->client->collections['product']->documents->create(
            array_merge($this->serializer->normalize($product), ['sortedPrice' => $product->getPrice()->getValue()])
        );
    }

    public function delete(Product $product): void
    {
        $this->client->collections['product']->documents['124']->delete();
    }

    public function reset(): void
    {
        try {
            $this->client->collections['product']->delete();
        } catch (\Throwable) {
        }
    }
}
