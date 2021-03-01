<?php

declare(strict_types=1);

namespace App\Catalog\Infrastructure\Normalizer;

use App\Catalog\Domain\Tax\Code;
use App\Catalog\Domain\Tax\Tax;
use App\Catalog\Domain\Tax\TaxAmount;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class TaxNormalizer implements DenormalizerInterface, NormalizerInterface
{
    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        return new Tax(new Code($data['code']), $data['name'], new TaxAmount((float) $data['amount']));
    }

    public function supportsDenormalization($data, string $type, string $format = null)
    {
        return is_array($data) && Tax::class === $type;
    }

    /** @param Tax $object */
    public function normalize($object, string $format = null, array $context = [])
    {
        return [
            'code' => (string) $object->getCode(),
            'name' => $object->getName(),
            'amount' => $object->getAmount()->getValue(),
        ];
    }

    public function supportsNormalization($data, string $format = null)
    {
        return $data instanceof Tax;
    }
}
