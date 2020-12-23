<?php

declare(strict_types=1);

namespace App\Dto\Response;

use App\Entity\Author;

class AuthorCreated
{
    public int $id;

    public function __construct(Author $author)
    {
        $this->id = $author->getId();
    }
}
