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
     * @param Enum $type
     */
    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        return $type::of($data);
    }

    public function supportsDenormalization($data, string $type, string $format = null)
    {
        return is_string($data) && Enum::class === get_parent_class($type);
    }

    /** @param Enum $object */
    public function normalize($object, string $format = null, array $context = [])
    {
        return $object->getValue();
    }

    public function supportsNormalization($data, string $format = null)
    {
        return $data instanceof Enum;
    }
}
