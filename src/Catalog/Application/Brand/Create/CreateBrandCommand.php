<?php

declare(strict_types=1);

namespace App\Catalog\Application\Brand\Create;

use App\Shared\Domain\Bus\Command\DomainCommand;

final class CreateBrandCommand implements DomainCommand
{
    public function __construct(private string $code, private string $name)
    {
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
