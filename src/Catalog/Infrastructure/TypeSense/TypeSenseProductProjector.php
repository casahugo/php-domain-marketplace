<?php

declare(strict_types=1);

namespace App\Catalog\Infrastructure\TypeSense;

use App\Catalog\Domain\Product\Product;
use App\Catalog\Domain\Product\ProductProjector;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Throwable;
use Typesense\Client;
use Typesense\Collection;

final class TypeSenseProductProjector implements ProductProjector
{
    public const INDEX = 'product';
    public const SORT = 'sorted_price';

    public function __construct(private Client $client, private NormalizerInterface $serializer)
    {
    }

    public function configure(): void
    {
        $this->client->collections->create([
            'name'      => 'product',
            'num_documents'=> 100,
            'fields'    => [
                [
                    'name'  => 'code',
                    'type'  => 'string',
                ],
                [
                    'name'  => 'name',
                    'type'  => 'string',
                ],
                [
                    'name'  => 'sorted_price',
                    'type'  => 'float',
                    'facet' => true,
                ],
                [
                    'name'  => 'stock',
                    'type'  => 'int32',
                    'facet' => true,
                ],
                [
                    'name'  => 'created_at',
                    'type'  => 'string',
                    'facet' => true,
                ],
            ],
            'default_sorting_field' => self::SORT,
        ]);
    }

    public function create(Product $product): void
    {
        $this->getCollection()->documents->create(
            array_merge(
                (array) $this->serializer->normalize($product),
                [
                    'sorted_price' => $product->getPrice()->getValue(),
                    'created_at' => $product->getCreatedAt()->format(DATE_ATOM),
                    'id' => (string) $product->getReference(),
                ],
            )
        );
    }

    public function delete(Product $product): void
    {
        $this->getCollection()
            ->documents[(string) $product->getReference()]
            ->delete()
        ;
    }

    public function reset(): void
    {
        try {
            $this->client->collections['product']->delete();
        } catch (Throwable) {
        }
    }

    private function getCollection(): Collection
    {
        return $this->client->collections[self::INDEX];
    }
}
