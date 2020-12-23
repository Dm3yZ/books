<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\Response\FoundBook;
use App\Exception\EntityNotFoundException;
use App\Repository\BookRepositoryInterface;
use Generator;

class BookSearchService
{
    private BookRepositoryInterface $bookRepository;

    public function __construct(BookRepositoryInterface $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    /**
     * @param string $bookName
     * @return Generator<FoundBook>
     */
    public function search(string $bookName): Generator
    {
        if (empty($bookName)) {
            return [];
        }

        $books = $this->bookRepository->findByName($bookName);
        foreach ($books as $book) {
            yield new FoundBook(
                $book->getId(),
                $book->getTranslations()->first()->getName(), //Название по которому происходил поиск
                $book->getAuthors()->toArray()
            );
        }
    }

    /**
     * @param int $id
     * @return FoundBook
     * @throws EntityNotFoundException
     */
    public function getLocalizedById(int $id): FoundBook
    {
        $book = $this->bookRepository->findById($id);
        if (! $book) {
            throw new EntityNotFoundException(sprintf('Book with id %d not found', $id));
        }

        return new FoundBook(
            $book->getId(),
            $book->translate()->getName(),
            $book->getAuthors()->toArray()
        );
    }
}
