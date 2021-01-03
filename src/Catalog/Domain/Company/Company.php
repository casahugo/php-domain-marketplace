<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Company;

use App\Shared\Domain\Email;

final class Company
{
    public function __construct(
        private Id $id,
        private Email $email,
        private string $name,
    ) {
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
