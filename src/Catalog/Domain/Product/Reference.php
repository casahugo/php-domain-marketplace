<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Product;

use App\Shared\Domain\Uuid\UuidInterface;

final class Reference
{
    public function __construct(private UuidInterface $value)
    {
    }

    public function getValue(): UuidInterface
    {
        return $this->value;
    }
}
