<?php

declare(strict_types=1);

namespace App\Catalog\Application\Product\Delete;

use App\Shared\Domain\Bus\Command\DomainCommand;
use App\Shared\Domain\Uuid\UuidInterface;

final class DeleteProductCommand implements DomainCommand
{
    public function __construct(private UuidInterface $reference)
    {
    }

    public function getReference(): UuidInterface
    {
        return $this->reference;
    }
}
