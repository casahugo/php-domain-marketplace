<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Product;

use App\Shared\Domain\{
    Aggregate,
    Event\Product\ProductPriceHasChanged,
    Event\Product\ProductStockHasChanged,
    Event\Product\ProductWasCreated,
    Uuid\UuidInterface
};
use App\Catalog\Domain\{
    Brand\Brand,
    Category\Category,
    Document\Document,
    Document\DocumentCollection,
    Picture\Picture,
    Picture\PictureCollection,
    Seller\Seller,
    Shipping\Shipping,
    Shipping\ShippingCollection,
    Tax\Tax,
    Tax\TaxCollection
};

final class Product extends Aggregate
{
    private Reference $reference;
    private string $name;
    private Code $code;
    private Seller $seller;
    private ?ProductPrice $originalPrice;
    private ProductPrice $price;
    private Stock $stock;
    private Brand $brand;
    private Category $category;
    private Status $status;
    private DocumentCollection $documents;
    private PictureCollection $gallery;
    private TaxCollection $taxes;
    private ?string $intro;
    private ?string $description;
    private ShippingCollection $shippings;
    private \DateTimeImmutable $createdAt;
    private ?\DateTimeImmutable $updatedAt;

    public function __construct(
        Reference $reference,
        Code $code,
        string $name,
        ProductPrice $price,
        Stock $stock,
        Brand $brand,
        Seller $seller,
        Category $category,
        TaxCollection $taxes,
        Status $status,
        \DateTimeImmutable $createdAt,
        ?\DateTimeImmutable $updatedAt = null,
        ?ShippingCollection $shippings = null,
        ?string $intro = null,
        ?string $description = null,
        ?ProductPrice $originalPrice = null,
        ?PictureCollection $pictures = null,
        ?DocumentCollection $documents = null,
    ) {
        $this->reference = $reference;
        $this->code = $code;
        $this->name = $name;
        $this->seller = $seller;
        $this->price = $price;
        $this->originalPrice = $originalPrice;
        $this->stock = $stock;
        $this->brand = $brand;
        $this->category = $category;
        $this->intro = $intro;
        $this->description = $description;
        $this->taxes = $taxes;
        $this->status = $status;
        $this->shippings = $shippings ?? new ShippingCollection();
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->gallery = $pictures ?? new PictureCollection();
        $this->documents = $documents ?? new DocumentCollection();
    }

    public static function create(
        UuidInterface $reference,
        string $code,
        string $name,
        float $price,
        int $stock,
        Brand $brand,
        Seller $seller,
        Category $category,
        TaxCollection $taxes,
        Status $status,
        \DateTimeImmutable $createdAt,
    ): self {
        $self = new self(
            new Reference($reference),
            new Code($code),
            $name,
            new ProductPrice($price),
            new Stock($stock),
            $brand,
            $seller,
            $category,
            $taxes,
            $status,
            $createdAt
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

    public function getPicture(): ?Picture
    {
        return $this->gallery->first();
    }

    public function getGallery(): PictureCollection
    {
        return $this->gallery;
    }

    public function addDocuments(Document ...$documents): self
    {
        $this->documents = $this->documents->add(...$documents);

        return $this;
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

    public function getShippings(): ?ShippingCollection
    {
        return $this->shippings;
    }

    public function addShipping(Shipping ...$shippings): self
    {
        $this->shippings = $this->shippings->add(...$shippings);

        return $this;
    }

    public function getSeller(): Seller
    {
        return $this->seller;
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
}
