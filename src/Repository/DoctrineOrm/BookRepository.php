<?php

declare(strict_types=1);

namespace App\Repository\DoctrineOrm;

use App\Entity\Book;
use App\Repository\BookRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository implements BookRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    public function findById(int $id): ?Book
    {
        return $this->find($id);
    }

    public function findByName(string $name): array
    {
        return $this->createQueryBuilder('b')
            ->select('b', 't')
            ->join('b.translations', 't')
            ->where('LOWER(t.name) LIKE LOWER(:name)')
            ->setParameter(':name', "%$name%")
            ->getQuery()
            ->getResult();
    }
}
