<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Author;

interface AuthorRepositoryInterface
{
    public function findById(int $id): ?Author;

    /**
     * @param array<string, mixed> $criteria
     * @return array<Author>
     */
    public function findByFields(array $criteria): array;
}
