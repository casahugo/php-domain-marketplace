<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Product;

use App\Shared\Domain\{
    Aggregate,
    Event\Product\ProductPriceHasChanged,
    Event\Product\ProductStockHasChanged,
    Event\Product\ProductWasCreated,
};
use App\Catalog\Domain\{
    Brand\Brand,
    Category\Category,
    Picture\Picture,
    Picture\PictureCollection,
    Company\Company,
    Shipping\Shipping,
    Tax\Tax,
    Tax\TaxCollection,
};

/**
 * Aggregate root.
 */
final class Product extends Aggregate
{
    private Reference $reference;
    private string $name;
    private Code $code;
    private Company $company;
    private ?ProductPrice $originalPrice;
    private ProductPrice $price;
    private Stock $stock;
    private Brand $brand;
    private Category $category;
    private Status $status;
    private PictureCollection $gallery;
    private TaxCollection $taxes;
    private ?string $intro;
    private ?string $description;
    private ?Shipping $shipping;
    private \DateTimeImmutable $createdAt;
    private ?\DateTimeImmutable $updatedAt;

    public function __construct(
        Reference $reference,
        Code $code,
        string $name,
        ProductPrice $price,
        Stock $stock,
        Brand $brand,
        Company $company,
        Category $category,
        TaxCollection $taxes,
        Status $status,
        \DateTimeImmutable $createdAt,
        ?\DateTimeImmutable $updatedAt = null,
        ?Shipping $shipping = null,
        ?string $intro = null,
        ?string $description = null,
        ?ProductPrice $originalPrice = null,
        ?PictureCollection $pictures = null,
    ) {
        $this->reference = $reference;
        $this->code = $code;
        $this->name = $name;
        $this->company = $company;
        $this->price = $price;
        $this->originalPrice = $originalPrice;
        $this->stock = $stock;
        $this->brand = $brand;
        $this->category = $category;
        $this->intro = $intro;
        $this->description = $description;
        $this->taxes = $taxes;
        $this->status = $status;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->shipping = $shipping;
        $this->gallery = $pictures ?? new PictureCollection();
    }

    public static function create(
        string $reference,
        string $code,
        string $name,
        float $price,
        int $stock,
        Brand $brand,
        Company $company,
        Category $category,
        TaxCollection $taxes,
        Status $status,
        \DateTimeImmutable $createdAt,
        ?Shipping $shipping = null,
        ?string $intro = null,
        ?string $description = null,
        ?float $originalPrice = null,
    ): self {
        $self = new self(
            Reference::fromString($reference),
            new Code($code),
            $name,
            new ProductPrice($price),
            new Stock($stock),
            $brand,
            $company,
            $category,
            $taxes,
            $status,
            $createdAt,
            null,
            $shipping,
            $intro,
            $description,
            isset($originalPrice) ? new ProductPrice($originalPrice) : null
        );

        $self->record(new ProductWasCreated($reference));

        return $self;
    }

    public function getReference(): Reference
    {
        return $this->reference;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCode(): Code
    {
        return $this->code;
    }

    public function getPrice(): ProductPrice
    {
        return $this->price;
    }

    public function getPriceWithTax(): ProductPrice
    {
        return $this->priceWithTax($this->price);
    }

    public function setPrice(ProductPrice $productPrice): self
    {
        $this->price = $productPrice;

        $this->record(new ProductPriceHasChanged(
            (string) $this->reference->getValue(),
            $this->price->getValue(),
            $this->getPriceWithTax()->getValue(),
        ));

        return $this;
    }

    public function getOriginalPrice(): ?ProductPrice
    {
        return $this->originalPrice;
    }

    public function getOriginalPriceWithTax(): ?ProductPrice
    {
        if (null === $this->originalPrice) {
            return null;
        }

        return $this->priceWithTax($this->originalPrice);
    }

    private function priceWithTax(ProductPrice $price): ProductPrice
    {
        /** @var Tax $tax */
        foreach ($this->taxes as $tax) {
            $price = $price->addTax($tax);
        }

        return $price;
    }

    public function setOriginalPrice(?ProductPrice $productPrice): self
    {
        $this->originalPrice = $productPrice;

        return $this;
    }

    public function getBrand(): Brand
    {
        return $this->brand;
    }

    public function getStock(): Stock
    {
        return $this->stock;
    }

    public function setStock(Stock $stock): self
    {
        $this->stock = $stock;

        $this->record(new ProductStockHasChanged((string) $this->reference, $this->stock->getValue()));

        return $this;
    }

    public function getCategory(): Category
    {
        return $this->category;
    }

    public function getPicture(): ?Picture
    {
        return $this->gallery->first();
    }

    public function getGallery(): PictureCollection
    {
        return $this->gallery;
    }

    public function addGallery(Picture ...$pictures): self
    {
        $this->gallery = $this->gallery->add(...$pictures);

        return $this;
    }

    public function getIntro(): ?string
    {
        return $this->intro;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getTaxes(): TaxCollection
    {
        return $this->taxes;
    }

    public function addTaxes(Tax ...$taxes): self
    {
        $this->taxes = $this->taxes->add(...$taxes);

        return $this;
    }

    public function getShipping(): ?Shipping
    {
        return $this->shipping;
    }

    public function setShipping(Shipping $shipping): self
    {
        $this->shipping = $shipping;

        return $this;
    }

    public function getCompany(): Company
    {
        return $this->company;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function setCategory(Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function setIntro(?string $intro): self
    {
        $this->intro = $intro;

        return $this;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
