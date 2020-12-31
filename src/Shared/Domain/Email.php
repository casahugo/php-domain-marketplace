<?php

declare(strict_types=1);

namespace App\Shared\Domain;

final class Email
{
    public function __construct(private string $value)
    {
        if (false === filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new \UnexpectedValueException("invalid format ($value)");
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
