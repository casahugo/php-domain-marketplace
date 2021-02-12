<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Symfony\Normalizer;

use App\Shared\Domain\DataStructure\Collection;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

final class CollectionNormalizer implements DenormalizerInterface, DenormalizerAwareInterface
{
    private DenormalizerInterface $denormalizer;

    public function denormalize($data, string $type, string $format = null, array $context = []): Collection
    {
        /** @var Collection $collection */
        $collection = new $type();

        $collection = $collection->add(...array_map(
            fn($item) => $this->denormalizer->denormalize($item, $collection->getType()),
            $data
        ));

        return $collection;
    }

    public function supportsDenormalization($data, string $type, string $format = null): bool
    {
        return is_array($data) && Collection::class === get_parent_class($type);
    }

    public function setDenormalizer(DenormalizerInterface $denormalizer): void
    {
        $this->denormalizer = $denormalizer;
    }
}
