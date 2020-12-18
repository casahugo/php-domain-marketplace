<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Category;

final class Category
{
    private Id $id;
    private ?string $name;

    public function __construct(
        Id $id,
        ?string $name = null
    ) {

        $this->id = $id;
        $this->name = $name;
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}