<?php

declare(strict_types=1);

namespace App\Shared\Domain\Storage;

interface FileStorage
{
    public function fileExists(string $location): bool;

    public function write(string $location, string $contents): void;

    public function read(string $location): mixed;

    public function delete(string $location): void;

    public function copy(string $source, string $destination): void;

    public function move(string $source, string $destination): void;
}
