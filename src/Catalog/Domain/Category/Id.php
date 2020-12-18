<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Category;

final class Id
{
    private int $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }
}