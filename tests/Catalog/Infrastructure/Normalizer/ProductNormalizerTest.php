<?php

declare(strict_types=1);

namespace App\Tests\Catalog\Infrastructure\Normalizer;

use App\Catalog\{
    Domain\Brand\Brand,
    Domain\Brand\Code as BrandCode,
    Domain\Category\Category,
    Domain\Category\Code as CategoryCode,
    Domain\Company\Company,
    Domain\Company\Id as CompanyId,
    Domain\Document\Document,
    Domain\Document\DocumentCollection,
    Domain\Document\Id as DocumentId,
    Domain\Picture\Id as PictureId,
    Domain\Picture\Picture,
    Domain\Picture\PictureCollection,
    Domain\Product\Code,
    Domain\Product\Product,
    Domain\Product\ProductPrice,
    Domain\Product\Reference,
    Domain\Product\Status,
    Domain\Product\Stock,
    Domain\Shipping\Shipping,
    Domain\Shipping\ShippingPrice,
    Domain\Tax\Code as CodeTax,
    Domain\Tax\Tax,
    Domain\Tax\TaxCollection,
    Domain\Tax\TaxAmount,
    Infrastructure\Normalizer\ProductNormalizer
};
use App\Shared\{
    Domain\Email,
    Infrastructure\Symfony\Normalizer\CollectionNormalizer,
    Infrastructure\Symfony\Normalizer\DecimalNormalizer,
    Infrastructure\Symfony\Normalizer\EnumNormalizer,
    Infrastructure\Symfony\Normalizer\IntegerValueNormalizer,
    Infrastructure\Symfony\Normalizer\StringeableNormalizer,
    Infrastructure\Symfony\Normalizer\UuidValueNormalizer,
    Infrastructure\Uuid\Uuid
};
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

final class ProductNormalizerTest extends TestCase
{
    private Serializer $serializer;

    public function setUp(): void
    {
        parent::setUp();
        $encoders = [new JsonEncoder()];

        $normalizers = [
            new EnumNormalizer(),
            new UuidValueNormalizer(),
            new StringeableNormalizer(),
            new IntegerValueNormalizer(),
            new DecimalNormalizer(),
            new DateTimeNormalizer(),
            new CollectionNormalizer(),
            new ProductNormalizer(),
            new ObjectNormalizer(),
        ];

        $this->serializer = new Serializer($normalizers, $encoders);
    }

    public function testEncode(): void
    {
        $data = $this->serializer->serialize($this->getProduct(), 'json');

        self::assertSame(
            json_decode($this->json(), true),
            json_decode($data, true)
        );
    }

    public function testDecoding(): void
    {
        $product = $this->serializer->deserialize($this->json(), Product::class, 'json');

        self::assertEquals(
            $this->getProduct(),
            $product
        );
    }

    private function json(): string
    {
        return <<<JSON
        {
           "reference":"01E439TP9XJZ9RPFH3T1PYBCR8",
           "name":"Laptop",
           "code":"code",
           "price":{
              "currency":"EUR",
              "value":12.1
           },
           "priceWithTax":{
              "currency":"EUR",
              "value":14.47
           },
           "originalPrice":{
              "currency":"EUR",
              "value":14
           },
           "originalPriceWithTax":{
              "currency":"EUR",
              "value":16.74
           },
           "brand":{
              "code":"TSB",
              "name":"Toshiba"
           },
           "stock":2,
           "category":{
              "code":"COMPUT",
              "name":"Computer"
           },
           "documents":[
              {
                 "id":"01E439TP9XJZ9RPFH3T1PYBCR8",
                 "path":"http:\/\/hosting.com\/document.pdf",
                 "title":"Document title"
              }
           ],
           "gallery":[
              {
                 "id":"01E439TP9XJZ9RPFH3T1PYBCR8",
                 "path":"http:\/\/hosting.com\/image.jpeg",
                 "title":"Image title"
              }
           ],
           "intro":"Presentation Laptop",
           "description":"Description Laptop",
           "taxes":[
              {
                 "code":"TVA_20",
                 "name":"TVA 20%",
                 "taxAmount":19.6,
                 "percentage":0.2
              }
           ],
           "shipping":{
              "code":"COL",
              "name":"Collissimo",
              "price": 12.23
           },
           "company":{
              "id":"01E439TP9XJZ9RPFH3T1PYBCR8",
              "email":"company@tld.com",
              "name":"Inc Corporation"
           },
           "status":"wait_moderation",
           "createdAt":"2020-01-01T00:00:00+00:00",
           "updatedAt":"2020-02-01T00:00:00+00:00"
        }
        JSON;
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
            new Category(new CategoryCode("COMPUT"), 'Computer'),
            (new TaxCollection())->add(new Tax(new CodeTax('TVA_20'), 'TVA 20%', new TaxAmount(19.6))),
            Status::WAIT_MODERATION(),
            new \DateTimeImmutable("2020-01-01"),
            new \DateTimeImmutable("2020-02-01"),
            new Shipping(
                new \App\Catalog\Domain\Shipping\Code('COL'),
                'Collissimo',
                new ShippingPrice(12.23)
            ),
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
