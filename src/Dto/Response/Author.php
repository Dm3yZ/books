<?php

declare(strict_types=1);

namespace App\Dto\Response;

class Author
{
    public int $id;
    public string $name;

    /**
     * Author constructor.
     * @param int $id
     * @param string $name
     */
    public function __construct(int $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }
}
