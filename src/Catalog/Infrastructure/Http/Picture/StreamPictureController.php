<?php

declare(strict_types=1);

namespace App\Catalog\Infrastructure\Http\Picture;

use App\Catalog\Domain\Exception\PictureNotFoundException;
use App\Catalog\Domain\Picture\Id;
use App\Catalog\Domain\Picture\PictureRepository;
use App\Shared\Domain\Storage\FileStorage;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class StreamPictureController
{
    public function __construct(
        private PictureRepository $repository,
        private FileStorage $storage
    ) {
    }

    public function __invoke(string $id): Response
    {
        try {
            $file = $this->repository->get(Id::fromString($id));
        } catch (PictureNotFoundException $exception) {
            return new JsonResponse(['message' => $exception->getMessage()], JsonResponse::HTTP_NOT_FOUND);
        }

        $stream = $this->storage->read($file->getPath());

        $headers = [];
        $headers['Content-Type'] = $file->getMimeType();

        if (null !== $file->getTitle()) {
            $headers['Content-Disposition'] = "attachment; filename=\"{$file->getTitle()}\"";
        }

        $response = new StreamedResponse(function() use ($stream) {
            echo stream_get_contents($stream);
        }, StreamedResponse::HTTP_OK);

        foreach ($headers as $name => $value) {
            $response->headers->set($name, $value);
        }

        return $response;
    }
}
