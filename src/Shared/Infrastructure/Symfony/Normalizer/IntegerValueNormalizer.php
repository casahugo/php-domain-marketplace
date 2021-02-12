<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Symfony\Normalizer;

use App\Shared\Domain\DataStructure\IntegerValue;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class IntegerValueNormalizer implements NormalizerInterface, DenormalizerInterface
{
    /** @param IntegerValue $object */
    public function normalize($object, string $format = null, array $context = []): int
    {
        return $object->getValue();
    }

    public function supportsNormalization($data, string $format = null)
    {
        return $data instanceof IntegerValue;
    }

    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        return new $type($data);
    }

    public function supportsDenormalization($data, string $type, string $format = null)
    {
        return is_int($data) && IntegerValue::class === get_parent_class($type);
    }
}
