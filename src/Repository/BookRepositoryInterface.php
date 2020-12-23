<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Book;

interface BookRepositoryInterface
{
    public function findById(int $id): ?Book;

    /**
     * @param string $name
     * @return array<Book>
     */
    public function findByName(string $name): array;
}
