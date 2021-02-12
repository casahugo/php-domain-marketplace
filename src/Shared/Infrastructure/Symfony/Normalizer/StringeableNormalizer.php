<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Symfony\Normalizer;

use App\Shared\Domain\DataStructure\UuidValue;
use Stringable;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class StringeableNormalizer implements NormalizerInterface, DenormalizerInterface
{
    public function normalize($object, string $format = null, array $context = []): string
    {
        return (string) $object;
    }

    public function supportsNormalization($data, string $format = null): bool
    {
        return $data instanceof \Stringable;
    }

    public function denormalize($data, string $type, string $format = null, array $context = []): object
    {
        return new $type($data);
    }

    public function supportsDenormalization($data, string $type, string $format = null): bool
    {
        return is_string($data)
            && UuidValue::class !== get_parent_class($type)
            && array_key_exists(Stringable::class, is_array(class_implements($type)) ? class_implements($type) : []);
    }
}
