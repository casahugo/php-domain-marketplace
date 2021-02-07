<?php

declare(strict_types=1);

namespace App\Catalog\Infrastructure\Normalizer;

use App\Catalog\Domain\Brand\Brand;
use App\Catalog\Domain\Category\Category;
use App\Catalog\Domain\Document\DocumentCollection;
use App\Catalog\Domain\Picture\PictureCollection;
use App\Catalog\Domain\Product\Code;
use App\Catalog\Domain\Product\Product;
use App\Catalog\Domain\Product\ProductPrice;
use App\Catalog\Domain\Product\Reference;
use App\Catalog\Domain\Product\Status;
use App\Catalog\Domain\Product\Stock;
use App\Catalog\Domain\Seller\Seller;
use App\Catalog\Domain\Shipping\ShippingCollection;
use App\Catalog\Domain\Tax\TaxCollection;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class ProductNormalizer implements NormalizerInterface, DenormalizerInterface, DenormalizerAwareInterface, NormalizerAwareInterface
{
    private DenormalizerInterface $denormalizer;
    private NormalizerInterface $normalizer;

    /** @param Product $product */
    public function normalize($product, string $format = null, array $context = []): array
    {
        return [
            "reference" => (string) $product->getReference(),
            "name" => $product->getName(),
            "code" => (string) $product->getCode(),
            "price" => $this->normalizer->normalize($product->getPrice()),
            "priceWithTax" => $this->normalizer->normalize($product->getPriceWithTax()),
            "originalPrice" => $this->normalizer->normalize($product->getOriginalPrice()),
            "originalPriceWithTax" => $this->normalizer->normalize($product->getOriginalPriceWithTax()),
            "brand" => $this->normalizer->normalize($product->getBrand()),
            "stock" => $this->normalizer->normalize($product->getStock()),
            "category" => $this->normalizer->normalize($product->getCategory()),
            "documents" => $this->normalizer->normalize($product->getDocuments()),
            "gallery" => $this->normalizer->normalize($product->getGallery()),
            "intro" => $product->getIntro(),
            "description" => $product->getDescription(),
            "taxes" => $this->normalizer->normalize($product->getTaxes()),
            "shippings" => $this->normalizer->normalize($product->getShippings()),
            "seller" => $this->normalizer->normalize($product->getSeller()),
            "status" => $this->normalizer->normalize($product->getStatus()),
            "createdAt" => $this->normalizer->normalize($product->getCreatedAt()),
            "updatedAt" => $this->normalizer->normalize($product->getUpdatedAt()),
        ];
    }

    public function supportsNormalization($data, string $format = null)
    {
        return $data instanceof Product;
    }

    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        return new Product(
            $this->denormalizer->denormalize($data['reference'], Reference::class),
            $this->denormalizer->denormalize($data['code'], Code::class),
            $data['name'],
            $this->denormalizer->denormalize($data['price'], ProductPrice::class),
            $this->denormalizer->denormalize($data['stock'], Stock::class),
            $this->denormalizer->denormalize($data['brand'], Brand::class),
            $this->denormalizer->denormalize($data['seller'], Seller::class),
            $this->denormalizer->denormalize($data['category'], Category::class),
            $this->denormalizer->denormalize($data['taxes'], TaxCollection::class),
            $this->denormalizer->denormalize($data['status'], Status::class),
            $this->denormalizer->denormalize($data['createdAt'], \DateTimeImmutable::class),
            isset($data['updatedAt']) ? $this->denormalizer->denormalize($data['updatedAt'], \DateTimeImmutable::class) : null,
            $this->denormalizer->denormalize($data['shippings'], ShippingCollection::class),
            $data['intro'] ?? null,
            $data['description'] ?? null,
            isset($data['originalPrice']) ? $this->denormalizer->denormalize($data['originalPrice'], ProductPrice::class) : null,
            $this->denormalizer->denormalize($data['gallery'], PictureCollection::class),
            $this->denormalizer->denormalize($data['documents'], DocumentCollection::class)
        );
    }

    public function supportsDenormalization($data, string $type, string $format = null)
    {
        return Product::class === $type;
    }

    public function setDenormalizer(DenormalizerInterface $denormalizer)
    {
        $this->denormalizer = $denormalizer;
    }

    public function setNormalizer(NormalizerInterface $normalizer)
    {
        $this->normalizer = $normalizer;
    }
}
