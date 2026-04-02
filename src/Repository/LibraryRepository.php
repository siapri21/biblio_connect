<?php

namespace App\Repository;

use App\Entity\Library;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Library>
 */
class LibraryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Library::class);
    }

    /**
     * @return Library[]
     */
    public function findAllOrderedByName(): array
    {
        return $this->createQueryBuilder('l')
            ->orderBy('l.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
