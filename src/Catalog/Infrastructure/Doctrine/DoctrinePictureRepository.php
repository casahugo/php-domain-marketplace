<?php

declare(strict_types=1);

namespace App\Catalog\Infrastructure\Doctrine;

use App\Catalog\Domain\Exception\PictureNotFoundException;
use App\Catalog\Domain\Picture\Id;
use App\Catalog\Domain\Picture\Picture;
use App\Catalog\Domain\Picture\PictureRepository;
use Doctrine\DBAL\Connection;

final class DoctrinePictureRepository implements PictureRepository
{
    public function __construct(private Connection $connection)
    {
    }

    public function get(Id $id): Picture
    {
        $picture = $this->connection->fetchAssociative(
            'SELECT id, name, path, mime_type FROM product_gallery WHERE id = :id',
            [':id' => (string) $id]
        );

        if (false === $picture) {
            throw new PictureNotFoundException((string) $id);
        }

        return new Picture(
            $id,
            $picture['path'],
            $picture['mime_type'],
            $picture['name'],
        );
    }
}
