<?php

declare(strict_types=1);

namespace App\Service;

use App\DataBase\ObjectManager;
use App\Dto\Request\CreateAuthor;
use App\Entity\Author;
use App\Exception\EntityAlreadyExists;
use App\Repository\AuthorRepositoryInterface;

class AuthorCreationService
{
    private AuthorRepositoryInterface $authorRepository;
    private ObjectManager $manager;

    public function __construct(AuthorRepositoryInterface $authorRepository, ObjectManager $objectManager)
    {
        $this->authorRepository = $authorRepository;
        $this->manager = $objectManager;
    }

    /**
     * @param CreateAuthor $createAuthor
     * @return Author
     * @throws EntityAlreadyExists
     */
    public function create(CreateAuthor $createAuthor): Author
    {
        $this->checkExistsAuthor($createAuthor);
        $author = new Author($createAuthor->name);

        $this->manager->save($author);
        $this->manager->flush();

        return $author;
    }

    /**
     * @param CreateAuthor $createAuthor
     * @throws EntityAlreadyExists
     */
    private function checkExistsAuthor(CreateAuthor $createAuthor): void
    {
        $existsAuthor = $this->authorRepository->findByFields(['name' => $createAuthor->name]);
        if ($existsAuthor) {
            throw new EntityAlreadyExists(sprintf('Author with name "%s" already exists', $createAuthor->name));
        }
    }
}
