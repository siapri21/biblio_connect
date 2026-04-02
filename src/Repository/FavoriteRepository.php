<?php

namespace App\Repository;

use App\Entity\Book;
use App\Entity\Favorite;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Favorite>
 */
class FavoriteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Favorite::class);
    }

    public function findOneByMemberAndBook(User $member, Book $book): ?Favorite
    {
        return $this->findOneBy(['member' => $member, 'book' => $book]);
    }

    /**
     * @return Favorite[]
     */
    public function findByMemberOrdered(User $member): array
    {
        return $this->createQueryBuilder('f')
            ->leftJoin('f.book', 'b')->addSelect('b')
            ->andWhere('f.member = :member')
            ->setParameter('member', $member)
            ->orderBy('f.createAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function countByMember(User $member): int
    {
        return (int) $this->createQueryBuilder('f')
            ->select('COUNT(f.id)')
            ->andWhere('f.member = :member')
            ->setParameter('member', $member)
            ->getQuery()
            ->getSingleScalarResult();
    }

    //    /**
    //     * @return Favorite[] Returns an array of Favorite objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('f.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Favorite
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
