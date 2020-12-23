<?php

declare(strict_types=1);

namespace App\Dto\Response;

use App\Entity\Book;

class BookCreated
{
    public int $id;

    public function __construct(Book $book)
    {
        $this->id = $book->getId();
    }
}
