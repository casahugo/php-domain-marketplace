<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Seller;

use App\Shared\Domain\Email;

final class Seller
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
