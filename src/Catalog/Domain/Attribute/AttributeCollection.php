<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Attribute;

use App\Shared\Domain\DataStructure\Collection;

final class AttributeCollection extends Collection
{
    public function __construct()
    {
        parent::__construct(Attribute::class);
    }
}
