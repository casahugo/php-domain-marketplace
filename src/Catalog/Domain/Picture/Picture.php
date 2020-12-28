<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Picture;

use App\Catalog\Domain\Document\Id;

final class Picture
{
    public function __construct(
        private Id $id,
        private string $host,
        private string $path,
        private string $title
    ) {
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getTitle(): string
    {
        return $this->title;
    }
}
