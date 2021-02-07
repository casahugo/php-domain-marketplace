<?php

declare(strict_types=1);

namespace App\Tests\Catalog\Domain\Product;

use App\Catalog\Domain\{
    Brand\Brand,
    Brand\Id as BrandId,
    Category\Category,
    Category\Id,
    Document\Document,
    Document\DocumentCollection,
    Document\Id as DocumentId,
    Picture\Id as PictureId,
    Picture\Picture,
    Picture\PictureCollection,
    Product\Code,
    Product\Product,
    Product\ProductPrice,
    Product\Reference,
    Product\Status,
    Product\Stock,
    Company\Id as SellerId,
    Company\Company,
    Shipping\Shipping,
    Shipping\ShippingCollection,
    Tax\Code as CodeTax,
    Tax\Tax,
    Tax\TaxCollection,
    Tax\TaxValue
};
use App\Shared\{
    Domain\Email,
    Domain\Event\Product\ProductStockHasChanged,
    Infrastructure\Uuid\Uuid};
use PHPUnit\Framework\TestCase;

final class ProductTest extends TestCase
{
    public function testConstructProduct(): void
    {
        $product = $this->getProduct();

        self::assertSame('01E439TP9XJZ9RPFH3T1PYBCR8', (string) $product->getReference());
        self::assertSame('code', (string) $product->getCode());
        self::assertSame('Laptop', $product->getName());
        self::assertSame('Presentation Laptop', $product->getIntro());
        self::assertSame('Description Laptop', $product->getDescription());
        self::assertSame(12.1, $product->getPrice()->getValue());
        self::assertSame(14.47, $product->getPriceWithTax()->getValue());
        self::assertSame(14., $product->getOriginalPrice()->getValue());
        self::assertSame(16.74, $product->getOriginalPriceWithTax()->getValue());
        self::assertSame('EUR', $product->getPrice()->getCurrency());
        self::assertSame('EUR', $product->getPriceWithTax()->getCurrency());
        self::assertSame('EUR', $product->getOriginalPrice()->getCurrency());
        self::assertSame('EUR', $product->getOriginalPriceWithTax()->getCurrency());
        self::assertSame(2, $product->getStock()->getValue());
        self::assertEquals(new \DateTimeImmutable("2020-01-01"), $product->getCreatedAt());
        self::assertEquals(new \DateTimeImmutable("2020-02-01"), $product->getUpdatedAt());
        self::assertSame(Status::WAIT_MODERATION(), $product->getStatus());
        self::assertSame(123, $product->getCompany()->getId()->getValue());
        self::assertSame('Inc Corporation', $product->getCompany()->getName());
        self::assertSame('company@tld.com', (string) $product->getCompany()->getEmail());

        self::assertCount(1, $product->getTaxes());
        /** @var Tax $tax */
        $tax = $product->getTaxes()->first();
        self::assertEquals('TVA_20', (string) $tax->getCode());
        self::assertEquals(19.6, $tax->getTaxValue()->getValue());

        self::assertSame($product->getPicture(), $product->getGallery()->first());
        self::assertCount(1, $product->getGallery());
        /** @var Picture $picture */
        $picture = $product->getGallery()->first();
        self::assertEquals('01E439TP9XJZ9RPFH3T1PYBCR8', (string) $picture->getId());
        self::assertEquals('http://hosting.com/image.jpeg', $picture->getPath());
        self::assertEquals('Image title', $picture->getTitle());

        self::assertCount(1, $product->getDocuments());
        /** @var Document $document */
        $document = $product->getDocuments()->first();
        self::assertEquals('01E439TP9XJZ9RPFH3T1PYBCR8', (string) $document->getId());
        self::assertEquals('http://hosting.com/document.pdf', $document->getPath());
        self::assertEquals('Document title', $document->getTitle());

        self::assertCount(1, $product->getShippings());

        self::assertSame(34, $product->getBrand()->getId()->getValue());
        self::assertSame('Toshiba', $product->getBrand()->getName());
        self::assertSame(2, $product->getCategory()->getId()->getValue());
        self::assertSame('Computer', $product->getCategory()->getName());
        self::assertSame(123, $product->getCompany()->getId()->getValue());
        self::assertSame('Inc Corporation', $product->getCompany()->getName());
        self::assertSame('company@tld.com', (string) $product->getCompany()->getEmail());
    }

    public function testAddPictureGallery(): void
    {
        $product = $this->getProduct();

        $product->addGallery($picture = new Picture(
            new PictureId(new Uuid('01E439TP9XJZ9RPFH3T1PYBCR8')),
            'http://hosting.com/image2.jpeg',
            'Second Image title'
        ));

        self::assertCount(2, $product->getGallery());
        self::assertSame($picture, $product->getGallery()->findFirst(fn(Picture $excepted) => $excepted === $picture));
    }

    public function testAddPictureDocument(): void
    {
        $product = $this->getProduct();

        $product->addDocuments($document = new Document(
            new DocumentId(new Uuid('01E439TP9XJZ9RPFH3T1PYBCR8')),
            'http://hosting.com/document2.pdf',
            'Second Document title'
        ));

        self::assertCount(2, $product->getDocuments());
        self::assertSame($document, $product->getDocuments()->findFirst(fn(Document $excepted) => $excepted === $document));
    }

    public function testChangeStock(): void
    {
        $product = $this->getProduct();

        $product->setStock(new Stock(5));
        $events = $product->pullDomainEvents();

        self::assertCount(1, $events);
        self::assertEquals(new ProductStockHasChanged((string) $product->getReference(), 5), $events[0]);

        self::assertSame(5, $product->getStock()->getValue());
    }

    private function getProduct(): Product
    {
        return new Product(
            new Reference($uuid = new Uuid('01E439TP9XJZ9RPFH3T1PYBCR8')),
            new Code('code'),
            'Laptop',
            new ProductPrice(12.1),
            new Stock(2),
            new Brand(new BrandId(34), 'Toshiba'),
            new Company(new SellerId(123), new Email('company@tld.com'), 'Inc Corporation'),
            new Category(new Id(2), 'Computer'),
            (new TaxCollection())->add(new Tax(new CodeTax('TVA_20'), new TaxValue(19.6))),
            Status::WAIT_MODERATION(),
            new \DateTimeImmutable("2020-01-01"),
            new \DateTimeImmutable("2020-02-01"),
            (new ShippingCollection())->add(new Shipping()),
            'Presentation Laptop',
            'Description Laptop',
            new ProductPrice(14),
            (new PictureCollection())->add(new Picture(
                new PictureId(new Uuid('01E439TP9XJZ9RPFH3T1PYBCR8')),
                'http://hosting.com/image.jpeg',
                'Image title'
            )),
            (new DocumentCollection())->add(new Document(
                new DocumentId(new Uuid('01E439TP9XJZ9RPFH3T1PYBCR8')),
                'http://hosting.com/document.pdf',
                'Document title'
            ))
        );
    }
}
