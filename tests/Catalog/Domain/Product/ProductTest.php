<?php

declare(strict_types=1);

namespace App\Tests\Catalog\Domain\Product;

use App\Catalog\Domain\{
    Brand\Brand,
    Brand\Code as BrandCode,
    Category\Category,
    Category\Code as CategoryCode,
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
    Company\Id as CompanyId,
    Company\Company,
    Shipping\Code as ShippingCode,
    Shipping\Shipping,
    Shipping\ShippingCollection,
    Shipping\ShippingPrice,
    Tax\Code as CodeTax,
    Tax\Tax,
    Tax\TaxCollection,
    Tax\TaxAmount};
use App\Shared\{
    Domain\Email,
    Domain\Event\Product\ProductPriceHasChanged,
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
        self::assertSame('01E439TP9XJZ9RPFH3T1PYBCR8', (string) $product->getCompany()->getId());
        self::assertSame('Inc Corporation', $product->getCompany()->getName());
        self::assertSame('company@tld.com', (string) $product->getCompany()->getEmail());

        self::assertCount(1, $product->getTaxes());
        /** @var Tax $tax */
        $tax = $product->getTaxes()->first();
        self::assertEquals('TVA_20', (string) $tax->getCode());
        self::assertEquals(19.6, $tax->getTaxAmount()->getValue());

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

        self::assertInstanceOf(Shipping::class, $product->getShipping());
        self::assertSame('COL', (string) $product->getShipping()->getCode());
        self::assertSame('Collissimo', $product->getShipping()->getName());
        self::assertSame(12.2, $product->getShipping()->getPrice()->getValue());

        self::assertSame("TSB", (string) $product->getBrand()->getCode());
        self::assertSame('Toshiba', $product->getBrand()->getName());
        self::assertSame('COMPUT', (string) $product->getCategory()->getCode());
        self::assertSame('Computer', $product->getCategory()->getName());
        self::assertSame('01E439TP9XJZ9RPFH3T1PYBCR8', (string) $product->getCompany()->getId());
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

    public function testChangePrice(): void
    {
        $product = $this->getProduct();

        $product->setPrice(new ProductPrice(25.99));
        $product->setOriginalPrice(null);
        $events = $product->pullDomainEvents();

        self::assertCount(1, $events);
        self::assertEquals(
            new ProductPriceHasChanged((string) $product->getReference(), 25.99, 31.08),
            $events[0]
        );

        self::assertSame(25.99, $product->getPrice()->getValue());
        self::assertSame(31.08, $product->getPriceWithTax()->getValue());
        self::assertNull($product->getOriginalPrice());
        self::assertNull($product->getOriginalPriceWithTax());
    }

    private function getProduct(): Product
    {
        return new Product(
            Reference::fromString('01E439TP9XJZ9RPFH3T1PYBCR8'),
            new Code('code'),
            'Laptop',
            new ProductPrice(12.1),
            new Stock(2),
            new Brand(new BrandCode('TSB'), 'Toshiba'),
            new Company(CompanyId::fromString('01E439TP9XJZ9RPFH3T1PYBCR8'), new Email('company@tld.com'), 'Inc Corporation'),
            new Category(new CategoryCode('COMPUT'), 'Computer'),
            (new TaxCollection())->add(new Tax(new CodeTax('TVA_20'), 'TVA 20%', new TaxAmount(19.6))),
            Status::WAIT_MODERATION(),
            new \DateTimeImmutable("2020-01-01"),
            new \DateTimeImmutable("2020-02-01"),
            new Shipping(new ShippingCode('COL'), 'Collissimo', new ShippingPrice(12.2)),
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
