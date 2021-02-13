<?php

declare(strict_types=1);

namespace App\Catalog\Infrastructure\Normalizer;

use App\Catalog\Domain\Tax\Code;
use App\Catalog\Domain\Tax\Tax;
use App\Catalog\Domain\Tax\TaxValue;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

final class TaxNormalizer implements DenormalizerInterface
{
    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        return new Tax(
            new Code($data['code']),
            new TaxValue($data['taxValue']),
        );
    }

    public function supportsDenormalization($data, string $type, string $format = null)
    {
        return is_array($data) && Tax::class === $type;
    }
}
