<?php

declare(strict_types=1);

namespace App\Catalog\Application\Shipping\Create;

use App\Shared\Domain\Bus\Command\DomainCommand;

final class CreateShippingCommand implements DomainCommand
{
    public function __construct(
        private string $code,
        private string $name,
        private float $price
    ) {
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): float
    {
        return $this->price;
    }
}
