<?php

declare(strict_types=1);

namespace App\Tests\Catalog;

use App\Catalog\Domain\Brand\Brand;
use App\Catalog\Domain\Brand\Code as BrandCode;
use App\Catalog\Domain\Category\Category;
use App\Catalog\Domain\Category\Code as CategoryCode;
use App\Catalog\Domain\Company\Company;
use App\Catalog\Domain\Company\Id;
use App\Catalog\Domain\Product\Code;
use App\Catalog\Domain\Product\Product;
use App\Catalog\Domain\Product\ProductPrice;
use App\Catalog\Domain\Product\Reference;
use App\Catalog\Domain\Product\Status;
use App\Catalog\Domain\Product\Stock;
use App\Catalog\Domain\Tax\TaxCollection;
use App\Shared\Domain\Email;

final class Factory
{
    public const PRODUCT_REFERENCE = '01E439TP9XJZ9RPFH3T1PYBCR8';
    public const PRODUCT_CODE = 'code';
    public const PRODUCT_NAME = 'Laptop';
    public const PRODUCT_PRICE = 12.1;
    public const PRODUCT_STOCK = 2;

    public const CATEGORY_CODE = 'HRDW';
    public const CATEGORY_NAME = 'Hardware';

    public const BRAND_CODE = 'SMSG';
    public const BRAND_NAME = 'Samsung';

    public const COMPANY_ID = '01E439TP9XJZ9RPFH3T1PYBCR8';
    public const COMPANY_EMAIL = 'company@tld.com';
    public const COMPANY_NAME = 'Inc Corporation';

    public static function getProduct(string $reference = self::PRODUCT_REFERENCE): Product
    {
        return new Product(
            Reference::fromString($reference),
            new Code(self::PRODUCT_CODE),
            self::PRODUCT_NAME,
            new ProductPrice(self::PRODUCT_PRICE),
            new Stock(self::PRODUCT_STOCK),
            self::getBrand(),
            new Company(Id::fromString(self::COMPANY_ID), new Email(self::COMPANY_EMAIL), self::COMPANY_NAME),
            self::getCategory(),
            new TaxCollection(),
            Status::WAIT_MODERATION(),
            new \DateTimeImmutable("2020-01-01"),
        );
    }

    public static function getProductArray(string $reference = self::PRODUCT_REFERENCE): array
    {
        return [
            'reference' => $reference,
            'code' => self::PRODUCT_CODE,
            'name' => self::PRODUCT_NAME,
            'price' => self::PRODUCT_PRICE,
            'stock' => self::PRODUCT_STOCK,
            'status' => 'wait_moderation',
            'created_at' => '2020-01-01',
            'category_code' => self::CATEGORY_CODE,
            'category_name' => self::CATEGORY_NAME,
            'brand_code' => self::BRAND_CODE,
            'brand_name' => self::BRAND_NAME,
            'company_id' => self::COMPANY_ID,
            'company_email' => self::COMPANY_EMAIL,
            'company_name' => self::COMPANY_NAME,
        ];
    }

    public static function getProductReference(string $reference = self::PRODUCT_REFERENCE): Reference
    {
        return Reference::fromString($reference);
    }

    public static function getProductJson(): string
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
                 "taxValue":19.6,
                 "percentage":0.2
              }
           ],
           "shippings":[],
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

    public static function getCategory(string $code = self::CATEGORY_CODE, string $name = self::CATEGORY_NAME): Category
    {
        return new Category(self::getCategoryCode($code), $name);
    }

    public static function getCategoryCode(string $code = self::CATEGORY_CODE): CategoryCode
    {
        return new CategoryCode($code);
    }

    public static function getBrand(string $code = self::BRAND_CODE, string $name = self::BRAND_NAME): Brand
    {
        return new Brand(self::getBrandCode($code), $name);
    }

    public static function getBrandCode(string $code = self::BRAND_CODE): BrandCode
    {
        return new BrandCode($code);
    }
}
