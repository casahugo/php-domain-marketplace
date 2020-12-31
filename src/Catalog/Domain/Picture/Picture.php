<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Picture;

final class Picture
{
    public function __construct(
        private Id $id,
        private string $path,
        private string $title
    ) {
    }

    public function getId(): Id
    {
        return $this->id;
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
