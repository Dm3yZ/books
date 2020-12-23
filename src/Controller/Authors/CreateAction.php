<?php

declare(strict_types=1);

namespace App\Controller\Authors;

use App\Dto\Request\CreateAuthor;
use App\Dto\Response\AuthorCreated;
use App\Exception\EntityAlreadyExists;
use App\Service\AuthorCreationService;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CreateAction
 * @package App\Controller\Authors
 *
 * @Route("/author/create", methods={"POST"})
 */
class CreateAction
{
    private AuthorCreationService $creationService;

    public function __construct(AuthorCreationService $authorCreationService)
    {
        $this->creationService = $authorCreationService;
    }

    /**
     * @param CreateAuthor $createAuthor
     * @return AuthorCreated
     * @throws EntityAlreadyExists
     */
    public function __invoke(CreateAuthor $createAuthor)
    {
        $author = $this->creationService->create($createAuthor);

        return new AuthorCreated($author);
    }
}
