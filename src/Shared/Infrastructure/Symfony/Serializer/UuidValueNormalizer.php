<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Symfony\Serializer;

use App\Shared\Domain\DataStructure\UuidValue;
use App\Shared\Infrastructure\Uuid\Uuid;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class UuidValueNormalizer implements NormalizerInterface, DenormalizerInterface
{
    /** @param UuidValue $object */
    public function normalize($object, string $format = null, array $context = [])
    {
        return (string) $object;
    }

    public function supportsNormalization($data, string $format = null): bool
    {
        return $data instanceof UuidValue;
    }

    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        return new $type(new Uuid($data));
    }

    public function supportsDenormalization($data, string $type, string $format = null): bool
    {
        return is_string($data) && UuidValue::class === get_parent_class($type) && Uuid::isValid($data);
    }
}
