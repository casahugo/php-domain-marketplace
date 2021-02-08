<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Symfony\Serializer;

use App\Shared\Domain\DataStructure\Enum;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class EnumNormalizer implements NormalizerInterface, DenormalizerInterface
{
    /**
     * @param string $data
     */
    public function denormalize($data, string $type, string $format = null, array $context = []): object
    {
        return $type::of($data);
    }

    public function supportsDenormalization($data, string $type, string $format = null): bool
    {
        return is_string($data) && Enum::class === get_parent_class($type);
    }

    /** @param Enum $object */
    public function normalize($object, string $format = null, array $context = []): float|int|string
    {
        return $object->getValue();
    }

    public function supportsNormalization($data, string $format = null): bool
    {
        return $data instanceof Enum;
    }
}
