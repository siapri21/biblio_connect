<?php

namespace App\Repository;

use App\Entity\Book;
use App\Entity\Review;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Review>
 */
class ReviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Review::class);
    }

    /**
     * @return Review[]
     */
    public function findVisibleByBookOrdered(Book $book): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.book = :b')
            ->andWhere('r.isVisible = true')
            ->setParameter('b', $book)
            ->orderBy('r.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findOneByUserAndBook(User $user, Book $book): ?Review
    {
        return $this->findOneBy(['reservedBy' => $user, 'book' => $book]);
    }

    /**
     * @return Review[]
     */
    public function findByMemberOrdered(User $member): array
    {
        return $this->createQueryBuilder('r')
            ->leftJoin('r.book', 'b')->addSelect('b')
            ->andWhere('r.reservedBy = :u')
            ->setParameter('u', $member)
            ->orderBy('r.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function countVisible(): int
    {
        return (int) $this->createQueryBuilder('r')
            ->select('COUNT(r.id)')
            ->andWhere('r.isVisible = true')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Tous les avis (pour modération admin), du plus récent au plus ancien.
     *
     * @return Review[]
     */
    public function findAllForModerationOrdered(): array
    {
        return $this->createQueryBuilder('r')
            ->leftJoin('r.book', 'b')->addSelect('b')
            ->leftJoin('r.reservedBy', 'u')->addSelect('u')
            ->orderBy('r.createdAt', 'DESC')
            ->addOrderBy('r.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function averageRatingForBook(Book $book): ?float
    {
        $result = $this->createQueryBuilder('r')
            ->select('AVG(r.rating)')
            ->andWhere('r.book = :b')
            ->andWhere('r.isVisible = true')
            ->setParameter('b', $book)
            ->getQuery()
            ->getSingleScalarResult();

        return $result !== null ? (float) $result : null;
    }

    public function countVisibleForBook(Book $book): int
    {
        return (int) $this->createQueryBuilder('r')
            ->select('COUNT(r.id)')
            ->andWhere('r.book = :b')
            ->andWhere('r.isVisible = true')
            ->setParameter('b', $book)
            ->getQuery()
            ->getSingleScalarResult();
    }

    //    /**
    //     * @return Review[] Returns an array of Review objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Review
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
