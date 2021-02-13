<?php

declare(strict_types=1);

namespace App\Shared\Domain\Event\Seller;

final class SellerWasCreated
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
