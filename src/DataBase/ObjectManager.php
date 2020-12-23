<?php

declare(strict_types=1);

namespace App\DataBase;

use Doctrine\ORM\EntityManagerInterface;

class ObjectManager
{
    private EntityManagerInterface $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function save(object $object): void
    {
        $this->manager->persist($object);
    }

    public function flush(): void
    {
        $this->manager->flush();
    }
}
