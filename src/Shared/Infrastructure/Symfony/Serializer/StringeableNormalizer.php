<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Symfony\Serializer;

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

    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        return new $type($data);
    }

    public function supportsDenormalization($data, string $type, string $format = null)
    {
        return is_string($data) && isset(class_implements($type)[\Stringable::class]);
    }
}
