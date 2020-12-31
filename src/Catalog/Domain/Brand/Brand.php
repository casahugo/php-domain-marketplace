<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Brand;

final class Brand
{
    public function __construct(private Id $id, private string $name)
    {
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
