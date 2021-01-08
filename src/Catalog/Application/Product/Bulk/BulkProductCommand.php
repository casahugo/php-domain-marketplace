<?php

declare(strict_types=1);

namespace App\Catalog\Application\Product\Bulk;

use App\Shared\Domain\Bus\Command\DomainCommand;

/**
 * Must be asynchronous.
 */
final class BulkProductCommand implements DomainCommand
{
    public function __construct(private ?string $importId, private int $companyId, private array $products)
    {
    }

    public function getImportId(): ?string
    {
        return $this->importId;
    }

    public function getProducts(): array
    {
        return $this->products;
    }

    public function getCompanyId(): int
    {
        return $this->companyId;
    }

    /** @return string[] */
    public function getProductsCode(): array
    {
        return array_column($this->products, 'code');
    }
}
