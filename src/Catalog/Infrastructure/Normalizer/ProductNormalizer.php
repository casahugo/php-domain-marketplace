<?php

declare(strict_types=1);

namespace App\Catalog\Infrastructure\Normalizer;

use App\Catalog\Domain\{
    Brand\Brand,
    Category\Category,
    Company\Company,
    Picture\PictureCollection,
    Product\Code,
    Product\Product,
    Product\ProductPrice,
    Product\Reference,
    Product\Status,
    Product\Stock,
    Shipping\Shipping,
    Tax\TaxCollection
};
use Symfony\Component\Serializer\Normalizer\{
    DenormalizerAwareInterface,
    DenormalizerInterface,
    NormalizerAwareInterface,
    NormalizerInterface
};

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
            "gallery" => $this->normalizer->normalize($product->getGallery()),
            "intro" => $product->getIntro(),
            "description" => $product->getDescription(),
            "taxes" => $this->normalizer->normalize($product->getTaxes()),
            "shipping" => $this->normalizer->normalize($product->getShipping()),
            "company" => $this->normalizer->normalize($product->getCompany()),
            "status" => $this->normalizer->normalize($product->getStatus()),
            "createdAt" => $this->normalizer->normalize($product->getCreatedAt()),
            "updatedAt" => $this->normalizer->normalize($product->getUpdatedAt()),
        ];
    }

    public function supportsNormalization($data, string $format = null): bool
    {
        return $data instanceof Product;
    }

    public function denormalize($data, string $type, string $format = null, array $context = []): Product
    {
        return new Product(
            $this->denormalizer->denormalize($data['reference'], Reference::class),
            $this->denormalizer->denormalize($data['code'], Code::class),
            $data['name'],
            $this->denormalizer->denormalize($data['price'], ProductPrice::class),
            $this->denormalizer->denormalize($data['stock'], Stock::class),
            $this->denormalizer->denormalize($data['brand'], Brand::class),
            $this->denormalizer->denormalize($data['company'], Company::class),
            $this->denormalizer->denormalize($data['category'], Category::class),
            $this->denormalizer->denormalize($data['taxes'], TaxCollection::class),
            $this->denormalizer->denormalize($data['status'], Status::class),
            $this->denormalizer->denormalize($data['createdAt'], \DateTimeImmutable::class),
            isset($data['updatedAt']) ? $this->denormalizer->denormalize($data['updatedAt'], \DateTimeImmutable::class) : null,
            isset($data['shipping']) ? $this->denormalizer->denormalize($data['shipping'], Shipping::class) : null,
            $data['intro'] ?? null,
            $data['description'] ?? null,
            isset($data['originalPrice']) ? $this->denormalizer->denormalize($data['originalPrice'], ProductPrice::class) : null,
            $this->denormalizer->denormalize($data['gallery'], PictureCollection::class),
        );
    }

    public function supportsDenormalization($data, string $type, string $format = null): bool
    {
        return Product::class === $type;
    }

    public function setDenormalizer(DenormalizerInterface $denormalizer): void
    {
        $this->denormalizer = $denormalizer;
    }

    public function setNormalizer(NormalizerInterface $normalizer): void
    {
        $this->normalizer = $normalizer;
    }
}
