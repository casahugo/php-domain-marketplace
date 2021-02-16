<?php

declare(strict_types=1);

namespace App\Catalog\Application\Tax\Create;

use App\Shared\Domain\Bus\Command\DomainCommand;

final class CreateTaxCommand implements DomainCommand
{
    public function __construct(private string $code, private string $name, private float $amount)
    {
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }
}
