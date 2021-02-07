<?php

declare(strict_types=1);

namespace App\Tests\Catalog\Infrastructure\Normalizer;

use App\Catalog\{
    Domain\Brand\Brand,
    Domain\Brand\Id as BrandId,
    Domain\Category\Category,
    Domain\Category\Id,
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
    Domain\Shipping\ShippingCollection,
    Domain\Tax\Code as CodeTax,
    Domain\Tax\Tax,
    Domain\Tax\TaxCollection,
    Domain\Tax\TaxValue,
    Infrastructure\Normalizer\DocumentNormalizer,
    Infrastructure\Normalizer\PictureNormalizer,
    Infrastructure\Normalizer\ProductNormalizer,
    Infrastructure\Normalizer\TaxNormalizer
};
use App\Shared\{
    Domain\Email,
    Infrastructure\Symfony\Serializer\CollectionNormalizer,
    Infrastructure\Symfony\Serializer\DecimalNormalizer,
    Infrastructure\Symfony\Serializer\EnumNormalizer,
    Infrastructure\Symfony\Serializer\IntegerValueNormalizer,
    Infrastructure\Symfony\Serializer\StringeableNormalizer,
    Infrastructure\Symfony\Serializer\UuidValueNormalizer,
    Infrastructure\Uuid\Uuid
};
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

final class ProductNormalizerTest extends TestCase
{
    public function testEncode(): void
    {
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

        $serializer = new Serializer($normalizers, $encoders);

        $data = $serializer->serialize($this->getProduct(), 'json');

        self::assertSame(
            json_decode($this->json(), true),
            json_decode($data, true)
        );
    }

    public function testDecoding(): void
    {
        $encoders = [new JsonEncoder()];

        $normalizers = [
            new EnumNormalizer(),
            new UuidValueNormalizer(),
            new StringeableNormalizer(),
            new IntegerValueNormalizer(),
            new DecimalNormalizer(),
            new DateTimeNormalizer(),
            new CollectionNormalizer(),
            new TaxNormalizer(),
            new PictureNormalizer(),
            new DocumentNormalizer(),
            new ProductNormalizer(),
            new ObjectNormalizer(),
        ];

        $serializer = new Serializer($normalizers, $encoders);

        $product = $serializer->deserialize($this->json(), Product::class, 'json');

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
              "id":34,
              "name":"Toshiba"
           },
           "stock":2,
           "category":{
              "id":2,
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
                 "taxValue":19.6,
                 "percentage":0.2
              }
           ],
           "shippings":[],
           "seller":{
              "id":123,
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
            new Reference($uuid = new Uuid('01E439TP9XJZ9RPFH3T1PYBCR8')),
            new Code('code'),
            'Laptop',
            new ProductPrice(12.1),
            new Stock(2),
            new Brand(new BrandId(34), 'Toshiba'),
            new Company(new CompanyId(123), new Email('company@tld.com'), 'Inc Corporation'),
            new Category(new Id(2), 'Computer'),
            (new TaxCollection())->add(new Tax(new CodeTax('TVA_20'), new TaxValue(19.6))),
            Status::WAIT_MODERATION(),
            new \DateTimeImmutable("2020-01-01"),
            new \DateTimeImmutable("2020-02-01"),
            new ShippingCollection(),
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
