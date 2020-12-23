<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Author;
use App\Entity\Book;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;

class BookFixtures extends Fixture
{
    private const BOOK_COUNT = 10_000;
    private const BATCH_SIZE = 100;

    private Generator $faker;
    private Generator $ruFaker;

    /**
     * AuthorFixtures constructor.
     * @param Generator $faker
     * @param Generator $ruFaker
     */
    public function __construct(Generator $faker, Generator $ruFaker)
    {
        $this->faker = $faker;
        $this->ruFaker = $ruFaker;
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < self::BOOK_COUNT; $i++) {
            $authors = $this->createAuthor();
            $book = new Book($authors);
            $book->translate('en')->setName($this->faker->text(50));
            $book->translate('ru')->setName($this->ruFaker->text(50));

            $manager->persist($book);
            $book->mergeNewTranslations();

            if ($i % self::BATCH_SIZE === 0) {
                $manager->flush();
                $manager->clear();
            }
        }

        $manager->flush();
    }

    /**
     * @return array<Author>
     */
    private function createAuthor(): array
    {
        static $authorsSequence = 1;
        $authorCount = random_int(1, 2);
        $authors = [];
        for ($i = 0; $i < $authorCount; $i++) {
            $authors[] = new Author($this->ruFaker->name . $authorsSequence);
            $authorsSequence++;
        }

        return $authors;
    }
}
