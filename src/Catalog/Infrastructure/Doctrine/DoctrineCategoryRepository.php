<?php

declare(strict_types=1);

namespace App\Catalog\Infrastructure\Doctrine;

use App\Catalog\Domain\Category\Category;
use App\Catalog\Domain\Category\CategoryRepository;
use App\Catalog\Domain\Category\Code;
use App\Catalog\Domain\Exception\CategoryNotFoundException;
use App\Catalog\Domain\Exception\CategorySaveFailedException;
use Doctrine\DBAL\Connection;

final class DoctrineCategoryRepository implements CategoryRepository
{
    public function __construct(private Connection $connection)
    {
    }

    public function get(Code $code): Category
    {
        $category = $this->connection->fetchAssociative("SELECT code, name FROM category WHERE code = :code", [
            'code' => (string) $code,
        ]);

        if (false === $category) {
            throw new CategoryNotFoundException("Category #$code not found", 404);
        }

        return new Category($code, $category['name']);
    }

    public function save(Category $category): void
    {
        $result = $this->connection->executeStatement(
            "INSERT IGNORE INTO category(code, name) VALUE (:code, :name)",
            [
                'code' => (string) $category->getCode(),
                'name' => $category->getName(),
            ]
        );

        if ($result === 0) {
            throw new CategorySaveFailedException((string) $category->getCode());
        }
    }
}
