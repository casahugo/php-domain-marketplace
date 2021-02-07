<?php

declare(strict_types=1);

namespace App\Catalog\Infrastructure\Normalizer;

use App\Catalog\Domain\Picture\Id;
use App\Catalog\Domain\Picture\Picture;
use App\Shared\Infrastructure\Uuid\Uuid;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

final class PictureNormalizer implements DenormalizerInterface
{
    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        return new Picture(
            new Id(new Uuid($data['id'])),
            $data['path'],
            $data['title'],
        );
    }

    public function supportsDenormalization($data, string $type, string $format = null)
    {
        return is_array($data) && Picture::class === $type;
    }
}
