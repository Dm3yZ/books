<?php

declare(strict_types=1);

namespace App\Controller\Books;

use App\Dto\Response\FoundBook;
use App\Exception\EntityNotFoundException;
use App\Service\BookSearchService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class GetBookAction
 * @package App\Controller\Books
 *
 * @Route("{lang}/book/{id}", methods={"GET"}, requirements={"lang"="en|ru"}, requirements={"id"="\d+"})
 */
class GetBookAction
{
    private BookSearchService $searchService;

    public function __construct(BookSearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    /**
     * @param string $lang
     * @param int $id
     * @param Request $request
     * @return FoundBook
     * @throws EntityNotFoundException
     */
    public function __invoke(string $lang, int $id, Request $request)
    {
        $request->setLocale($lang);

        return $this->searchService->getLocalizedById($id);
    }
}
