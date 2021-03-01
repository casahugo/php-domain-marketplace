<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Storage;

use App\Shared\Domain\Storage\FileStorage;
use League\Flysystem\FilesystemOperator;

final class LeagueFileSystemStorage implements FileStorage
{
    public function __construct(private FilesystemOperator $defaultStorage)
    {
    }

    public function fileExists(string $location): bool
    {
        return $this->defaultStorage->fileExists($location);
    }

    public function write(string $location, string $contents): void
    {
        $stream = fopen($contents, 'r+');

        if (is_resource($stream)) {
            $this->defaultStorage->writeStream($location, $stream);
            fclose($stream);
        } else {
            $this->defaultStorage->write($location, $contents);
        }
    }

    public function read(string $location): mixed
    {
        return $this->defaultStorage->readStream($location);
    }

    public function delete(string $location): void
    {
        $this->defaultStorage->delete($location);
    }

    public function copy(string $source, string $destination): void
    {
        $this->defaultStorage->copy($source, $destination);
    }

    public function move(string $source, string $destination): void
    {
        $this->defaultStorage->move($source, $destination);
    }
}
