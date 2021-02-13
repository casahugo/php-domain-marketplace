<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Category;

final class Category
{
    public function __construct(
        private Code $code,
        private string $name
    ) {
    }

    public function getCode(): Code
    {
        return $this->code;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
