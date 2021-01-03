<?php

declare(strict_types=1);

namespace App\Catalog\Application\Product\Create;

use App\Shared\Domain\Bus\Command\DomainCommand;

final class CreateProductCommand implements DomainCommand
{
    public function __construct(
        private string $reference,
        private string $code,
        private string $name,
        private float $price,
        private int $stock,
        private int $brandId,
        private int $categoryId,
        private int $companyId,
        private array $taxCodes,
        private ?array $shippingId = null,
        private ?string $intro = null,
        private ?string $description = null,
        private ?float $originalPrice = null,
    ) {
    }

    public function getReference(): string
    {
        return $this->reference;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getStock(): int
    {
        return $this->stock;
    }

    public function getTaxCodes(): array
    {
        return $this->taxCodes;
    }

    public function getBrandId(): int
    {
        return $this->brandId;
    }

    public function getCategoryId(): int
    {
        return $this->categoryId;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getCompanyId(): int
    {
        return $this->companyId;
    }

    public function getIntro(): ?string
    {
        return $this->intro;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getOriginalPrice(): ?float
    {
        return $this->originalPrice;
    }

    public function getShippingId(): ?array
    {
        return $this->shippingId;
    }
}
