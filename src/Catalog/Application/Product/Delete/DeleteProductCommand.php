<?php

declare(strict_types=1);

namespace App\Catalog\Application\Product\Delete;

use App\Shared\Domain\Bus\Command\DomainCommand;

final class DeleteProductCommand implements DomainCommand
{
    public function __construct(private string $reference)
    {
    }

    public function getReference(): string
    {
        return $this->reference;
    }
}
