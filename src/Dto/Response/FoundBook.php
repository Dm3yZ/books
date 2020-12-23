<?php

declare(strict_types=1);

namespace App\Dto\Response;

class FoundBook
{
    public int $id;
    public string $name;

    /**
     * @var array<Author>
     */
    public array $authors;

    /**
     * FoundBooks constructor.
     * @param int $id
     * @param string $name
     * @param array $authorEntityList
     */
    public function __construct(int $id, string $name, array $authorEntityList)
    {
        $this->id = $id;
        $this->name = $name;

        foreach ($authorEntityList as $authorEntity) {
            $this->authors[] = new Author($authorEntity->getId(), $authorEntity->getName());
        }
    }
}
