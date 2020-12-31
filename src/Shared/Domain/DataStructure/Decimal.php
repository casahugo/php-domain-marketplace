<?php

declare(strict_types=1);

namespace App\Shared\Domain\DataStructure;

abstract class Decimal
{
    public function __construct(protected int|float $value)
    {
    }

    public function getValue(): int|float
    {
        return round($this->value, 2);
    }

    public function addition(Decimal $decimal): self
    {
        $self = clone $this;

        $self->value = $self->value + $decimal->value;

        return $self;
    }

    public function multiply(Decimal $multiple): self
    {
        $self = clone $this;

        $self->value = $self->value * $multiple->value;

        return $self;
    }

    public function divide(float $divider): self
    {
        $self = clone $this;

        if ($divider === 0.) {
            throw new \LogicException("Divider must be greater than 0");
        }

        $self->value = $self->value / $divider;

        return $self;
    }
}
