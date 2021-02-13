<?php

declare(strict_types=1);

namespace App\Catalog\Application\Company\Create;

use App\Shared\Domain\Bus\Command\DomainCommand;

final class CreateCompanyCommand implements DomainCommand
{
    public function __construct(
        private string $id,
        private string $email,
        private string $name,
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
