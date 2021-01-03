<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Category;

final class Category
{
    public function __construct(
        private Id $id,
        private string $name
    ) {
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
