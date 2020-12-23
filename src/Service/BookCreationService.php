<?php

declare(strict_types=1);

namespace App\Service;

use App\DataBase\ObjectManager;
use App\Dto\Request\CreateBook;
use App\Entity\Author;
use App\Entity\Book;
use App\Exception\EntityNotFoundException;
use App\Repository\AuthorRepositoryInterface;

class BookCreationService
{
    private AuthorRepositoryInterface $authorRepository;
    private ObjectManager $manager;

    public function __construct(AuthorRepositoryInterface $authorRepository, ObjectManager $manager)
    {
        $this->authorRepository = $authorRepository;
        $this->manager = $manager;
    }

    /**
     * @param CreateBook $createBook
     * @return Book
     * @throws EntityNotFoundException
     */
    public function create(CreateBook $createBook): Book
    {
        $authors = $this->getAuthors($createBook);
        $book = new Book($authors);
        foreach ($createBook->localizations as $localization) {
            $book->translate($localization->locale)->setName($localization->name);
        }

        $this->manager->save($book);
        $book->mergeNewTranslations();
        $this->manager->flush();

        return $book;
    }

    /**
     * @param CreateBook $createBook
     * @return array<Author>
     * @throws EntityNotFoundException
     */
    private function getAuthors(CreateBook $createBook): array
    {
        $authors = $this->authorRepository->findByFields(['id' => $createBook->authorIds]);
        $foundIds = array_map(static fn(Author $author) => $author->getId(), $authors);
        $notFound = array_diff($createBook->authorIds, $foundIds);

        if (count($notFound) > 0) {
            throw new EntityNotFoundException(sprintf('Author with ids: [%d] not found', implode(', ', $notFound)));
        }

        return $authors;
    }
}
