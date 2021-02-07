<?php

declare(strict_types=1);

namespace App\Catalog\Infrastructure\Http\Product;

use Symfony\Component\HttpFoundation\JsonResponse;
use Typesense\Client;

final class SearchProductController
{
    public function __construct(private Client $client)
    {
    }

    public function __invoke(): JsonResponse
    {
        $parameters = [
            'q'         =>  '',
            'page'        =>  '1',
            'query_by'         =>  'name',
            'facet_by' => 'stock, sorted_price',
        ];

        return new JsonResponse($this->client->collections['product']->documents->search($parameters));
    }
}
