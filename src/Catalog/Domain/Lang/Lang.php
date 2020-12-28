<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Lang;

final class Lang
{
    public function __construct(private string $lang)
    {
    }

    public function __toString(): string
    {
        return $this->lang;
    }
}
