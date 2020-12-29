<?php

declare(strict_types=1);

namespace App\Shared\Domain\DataStructure;

abstract class IntegerValue
{
    public function __construct(private int $value)
    {
    }

    public function getId(): int
    {
        return $this->value;
    }
}
