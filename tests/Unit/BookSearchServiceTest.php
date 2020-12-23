<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Entity\Author;
use App\Entity\Book;
use App\Exception\EntityNotFoundException;
use App\Repository\BookRepositoryInterface;
use App\Service\BookSearchService;
use PHPUnit\Framework\TestCase;

class BookSearchServiceTest extends TestCase
{
    private $repository;
    private BookSearchService $service;

    protected function setUp()
    {
        $this->repository = $this->getMockBuilder(BookRepositoryInterface::class)->getMock();
        $this->service = new BookSearchService($this->repository);
    }

    public function testSearch(): void
    {
        $author = new Author('Лев Толстой');
        $author->setId(1);
        $book = new Book([$author]);
        $book->setId(1);
        $book->translate('ru')->setName('Война и Мир');
        $book->mergeNewTranslations();
        $this->repository->method('findByName')->willReturn([$book]);

        $result = iterator_to_array($this->service->search('война'));

        $this->assertCount(1, $result);
        $this->assertSame(1, $result[0]->id);
        $this->assertSame('Война и Мир', $result[0]->name);
        $this->assertCount(1, $result[0]->authors);
        $this->assertSame(1, $result[0]->authors[0]->id);
        $this->assertSame('Лев Толстой', $result[0]->authors[0]->name);
    }

    public function testSearchWhenEmptyNameShouldGiveEmptyGenerator(): void
    {
        $result = $this->service->search('');

        $this->assertEmpty(iterator_to_array($result));
    }

    public function testGetLocalizedById(): void
    {
        $author = new Author('Лев Толстой');
        $author->setId(1);
        $book = new Book([$author]);
        $book->setId(1);
        $book->translate('en')->setName('War and peace');
        $book->mergeNewTranslations();
        $this->repository->method('findById')->willReturn($book);

        $result = $this->service->getLocalizedById(1);

        $this->assertSame(1, $result->id);
        $this->assertSame('War and peace', $result->name);
        $this->assertCount(1, $result->authors);
        $this->assertSame(1, $result->authors[0]->id);
        $this->assertSame('Лев Толстой', $result->authors[0]->name);
    }

    public function testGetLocalizedByIdWhenBookDoesntExistsShouldThrowException(): void
    {
        $this->repository->method('findById')->willReturn(null);

        $this->expectException(EntityNotFoundException::class);
        $this->expectExceptionMessage('Book with id 2 not found');
        $this->service->getLocalizedById(2);
    }
}
