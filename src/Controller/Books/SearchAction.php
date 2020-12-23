<?php

declare(strict_types=1);

namespace App\Controller\Books;

use App\Dto\Response\FoundBook;
use App\Service\BookSearchService;
use Generator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CreateAction
 * @package App\Controller\Books
 *
 * @Route("/book/search", methods={"GET"})
 */
class SearchAction
{
    private BookSearchService $searchService;

    /**
     * SearchAction constructor.
     * @param BookSearchService $bookSearchService
     */
    public function __construct(BookSearchService $bookSearchService)
    {
        $this->searchService = $bookSearchService;
    }

    /**
     * @param Request $request
     * @return Generator<FoundBook>
     */
    public function __invoke(Request $request): Generator
    {
        $bookName = $request->get('name', '');

        yield $this->searchService->search($bookName);
    }
}
