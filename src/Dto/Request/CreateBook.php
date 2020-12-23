<?php

declare(strict_types=1);

namespace App\Dto\Request;

use Symfony\Component\Validator\Constraints as Assert;

class CreateBook
{
    /**
     * @Assert\NotBlank
     * @var array<BookLocalization>
     */
    public array $localizations = [];

    /**
     * @var array<int>
     * @Assert\NotBlank
     * @Assert\All({
     *     @Assert\Type("integer")
     *})
     */
    public array $authorIds = [];
}
