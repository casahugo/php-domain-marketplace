<?php

declare(strict_types=1);

namespace App\Catalog\Infrastructure\Normalizer;

use App\Catalog\Domain\Document\Document;
use App\Catalog\Domain\Document\Id;
use App\Shared\Infrastructure\Uuid\Uuid;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

final class DocumentNormalizer implements DenormalizerInterface
{
    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        return new Document(
            new Id(new Uuid($data['id'])),
            $data['path'],
            $data['title'],
        );
    }

    public function supportsDenormalization($data, string $type, string $format = null)
    {
        return is_array($data) && Document::class === $type;
    }
}
