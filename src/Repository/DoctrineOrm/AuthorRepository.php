<?php

declare(strict_types=1);

namespace App\Repository\DoctrineOrm;

use App\Entity\Author;
use App\Repository\AuthorRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Author|null find($id, $lockMode = null, $lockVersion = null)
 * @method Author|null findOneBy(array $criteria, array $orderBy = null)
 * @method Author[]    findAll()
 * @method Author[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuthorRepository extends ServiceEntityRepository implements AuthorRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
    }

    public function findById(int $id): ?Author
    {
        return $this->find($id);
    }

    public function findByFields(array $criteria): array
    {
        return $this->findBy($criteria);
    }
}
