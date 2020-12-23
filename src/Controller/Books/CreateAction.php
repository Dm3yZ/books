<?php

declare(strict_types=1);

namespace App\Controller\Books;

use App\Dto\Request\CreateBook;
use App\Dto\Response\BookCreated;
use App\Exception\EntityNotFoundException;
use App\Service\BookCreationService;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CreateAction
 * @package App\Controller\Books
 *
 * @Route("/book/create", methods={"POST"})
 */
class CreateAction
{
    private BookCreationService $creationService;

    public function __construct(BookCreationService $bookService)
    {
        $this->creationService = $bookService;
    }

    /**
     * @param CreateBook $createBook
     * @return BookCreated
     * @throws EntityNotFoundException
     */
    public function __invoke(CreateBook $createBook)
    {
        $book = $this->creationService->create($createBook);

        return new BookCreated($book);
    }
}
