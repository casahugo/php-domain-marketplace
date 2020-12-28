<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Product;

use App\Shared\Domain\{
    Entity,
    Event\Product\ProductPriceHasChanged,
    Event\Product\ProductStockHasChanged,
    Event\Product\ProductWasCreated,
    Uuid\UuidInterface};
use App\Catalog\Domain\{
    Attribute\AttributeCollection,
    Category\Category,
    Document\Document,
    Document\DocumentCollection,
    Picture\Picture,
    Picture\PictureCollection,
    Seller\Seller,
    Shipping\Shipping,
    Tax\TaxCollection
};

final class Product extends Entity
{
    private Reference $reference;
    private string $name;
    private Code $code;
    private Seller $seller;
    private ?ProductPrice $originalPrice;
    private ProductPrice $price;
    private Stock $stock;
    private Category $category;
    private Status $status;
    private DocumentCollection $documents;
    private PictureCollection $pictures;
    private AttributeCollection $attributes;
    private TaxCollection $taxes;
    private ?string $intro;
    private ?string $description;
    private ?Shipping $shipping;
    private \DateTimeImmutable $createdAt;
    private \DateTimeImmutable $updatedAt;

    public function __construct(
        Reference $reference,
        Code $code,
        string $name,
        ProductPrice $price,
        Stock $stock,
        Seller $seller,
        Category $category,
        TaxCollection $taxes,
        Status $status,
        ?string $intro = null,
        ?string $description = null,
        ?ProductPrice $originalPrice = null,
        ?PictureCollection $pictures = null,
        ?DocumentCollection $documents = null,
        ?AttributeCollection $attributes = null,
    ) {
        $this->reference = $reference;
        $this->code = $code;
        $this->name = $name;
        $this->seller = $seller;
        $this->price = $price;
        $this->originalPrice = $originalPrice;
        $this->stock = $stock;
        $this->category = $category;
        $this->intro = $intro;
        $this->description = $description;
        $this->taxes = $taxes;
        $this->status = $status;
        $this->pictures = $pictures ?? new PictureCollection();
        $this->documents = $documents ?? new DocumentCollection();
        $this->attributes = $attributes ?? new AttributeCollection();
    }

    public static function create(
        UuidInterface $reference,
        string $code,
        string $name,
        float $price,
        int $stock,
        Seller $seller,
        Category $category,
        TaxCollection $taxes,
        Status $status,
    ): self {
        $self = new self(
            new Reference($reference),
            new Code($code),
            $name,
            new ProductPrice($price),
            new Stock($stock),
            $seller,
            $category,
            $taxes,
            $status,
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
        $price = $this->price;
        foreach ($this->taxes as $tax) {
            // $price = $this->price->multiply($tax);
        }

        return $price;
    }

    public function setPrice(ProductPrice $productPrice): self
    {
        $this->price = $productPrice;

        $this->record(new ProductPriceHasChanged(
            $this->reference->getValue(),
            $this->price,
            $this->getPriceWithTax()
        ));

        return $this;
    }

    public function getOriginalPrice(): ?ProductPrice
    {
        return $this->originalPrice;
    }

    public function setOriginalPrice(?ProductPrice $productPrice): self
    {
        $this->originalPrice = $productPrice;

        return $this;
    }

    public function getStock(): Stock
    {
        return $this->stock;
    }

    public function setStock(Stock $stock): self
    {
        $this->stock = $stock;

        $this->record(new ProductStockHasChanged($this->reference->getValue(), $this->stock->getValue()));

        return $this;
    }

    public function getCategory(): Category
    {
        return $this->category;
    }

    public function getDocuments(): DocumentCollection
    {
        return $this->documents;
    }

    public function getPictures(): PictureCollection
    {
        return $this->pictures;
    }

    public function addDocuments(Document ...$documents): self
    {
        $this->documents = $this->documents->add(...$documents);

        return $this;
    }

    public function addPictures(Picture ...$pictures): self
    {
        $this->pictures = $this->pictures->add(...$pictures);

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

    public function getAttributes(): AttributeCollection
    {
        return $this->attributes;
    }

    public function getTaxes(): TaxCollection
    {
        return $this->taxes;
    }

    public function getShipping(): ?Shipping
    {
        return $this->shipping;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
