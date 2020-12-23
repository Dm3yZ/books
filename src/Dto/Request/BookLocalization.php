<?php

namespace App\Dto\Request;

use Symfony\Component\Validator\Constraints as Assert;

class BookLocalization
{
    /**
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 2,
     *      max = 255
     * )
     */
    public string $name = '';

    /**
     * @Assert\NotBlank
     * @Assert\Choice({"en", "ru"})
     */
    public string $locale = '';

    /**
     * BookLocalization constructor.
     * @param string $name
     * @param string $locale
     */
    public function __construct(string $name, string $locale)
    {
        $this->name = $name;
        $this->locale = $locale;
    }
}
