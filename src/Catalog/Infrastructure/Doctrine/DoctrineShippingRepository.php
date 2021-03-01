<?php

declare(strict_types=1);

namespace App\Catalog\Infrastructure\Doctrine;

use App\Catalog\Domain\{
    Exception\ShippingNotFoundException,
    Exception\ShippingSaveFailedException,
    Shipping\Code,
    Shipping\Shipping,
    Shipping\ShippingPrice,
    Shipping\ShippingRepository
};
use Doctrine\DBAL\Connection;

final class DoctrineShippingRepository implements ShippingRepository
{
    public function __construct(private Connection $connection)
    {
    }

    public function get(Code $code): Shipping
    {
        $shipping = $this->connection->fetchAssociative("SELECT code, name, price FROM shipping WHERE code = :code", [
            'code' => (string) $code,
        ]);

        if (false === $shipping) {
            throw new ShippingNotFoundException("Brand #$code not found", 404);
        }

        return new Shipping($code, $shipping['name'], new ShippingPrice((float) $shipping['price']));
    }

    public function save(Shipping $shipping): void
    {
        try {
            $this->connection->insert('shipping', [
                'code' => (string) $shipping->getCode(),
                'name' => $shipping->getName(),
                'price' => $shipping->getPrice()->getValue(),
            ]);
        } catch (\Throwable $exception) {
            throw new ShippingSaveFailedException((string) $shipping->getCode(), $exception);
        }
    }
}
