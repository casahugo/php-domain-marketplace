<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Symfony\Serializer;

use App\Shared\Domain\DataStructure\Decimal;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class DecimalNormalizer implements NormalizerInterface, DenormalizerInterface
{
    /** @param Decimal $object */
    public function normalize($object, string $format = null, array $context = []): float|int
    {
        return $object->getValue();
    }

    public function supportsNormalization($data, string $format = null)
    {
        return $data instanceof Decimal && false === method_exists($data, 'getCurrency');
    }

    /**
     * @param int|float $data
     * @param Decimal $type
     */
    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        return new $type($data);
    }

    public function supportsDenormalization($data, string $type, string $format = null)
    {
        return (is_float($data) || is_int($data)) && Decimal::class === get_parent_class($type);
    }
}
