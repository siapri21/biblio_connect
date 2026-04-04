<?php

namespace App\Repository;

use App\Entity\Book;
use App\Entity\Reservation;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reservation>
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    /**
     * @return Reservation[]
     */
    public function findByMemberOrdered(User $member): array
    {
        return $this->createQueryBuilder('r')
            ->leftJoin('r.book', 'b')->addSelect('b')
            ->leftJoin('b.library', 'l')->addSelect('l')
            ->andWhere('r.member = :m')
            ->setParameter('m', $member)
            ->orderBy('r.startAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function countActiveForBook(Book $book): int
    {
        return (int) $this->createQueryBuilder('r')
            ->select('COUNT(r.id)')
            ->andWhere('r.book = :book')
            ->andWhere('r.status IN (:st)')
            ->setParameter('book', $book)
            ->setParameter('st', ['pending', 'confirmed'])
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Réservation encore en cours (en attente ou confirmée) pour cet usager et ce livre.
     */
    public function countByStatus(string $status): int
    {
        return (int) $this->createQueryBuilder('r')
            ->select('COUNT(r.id)')
            ->andWhere('r.status = :s')
            ->setParameter('s', $status)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countTotal(): int
    {
        return (int) $this->createQueryBuilder('r')
            ->select('COUNT(r.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Prêts confirmés dont la date de fin est dépassée (retour attendu).
     */
    public function countOverdueConfirmed(): int
    {
        $today = new \DateTimeImmutable('today');

        return (int) $this->createQueryBuilder('r')
            ->select('COUNT(r.id)')
            ->andWhere('r.status = :st')
            ->andWhere('r.endAt < :today')
            ->setParameter('st', 'confirmed')
            ->setParameter('today', $today)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function hasActiveReservationForMemberAndBook(User $member, Book $book): bool
    {
        $count = (int) $this->createQueryBuilder('r')
            ->select('COUNT(r.id)')
            ->andWhere('r.member = :member')
            ->andWhere('r.book = :book')
            ->andWhere('r.status IN (:st)')
            ->setParameter('member', $member)
            ->setParameter('book', $book)
            ->setParameter('st', ['pending', 'confirmed'])
            ->getQuery()
            ->getSingleScalarResult();

        return $count > 0;
    }

    /**
     * Historique complet pour le personnel (bibliothécaires / admins).
     *
     * @return Reservation[]
     */
    public function findAllForStaffOrdered(?string $status = null): array
    {
        $qb = $this->createQueryBuilder('r')
            ->leftJoin('r.member', 'm')->addSelect('m')
            ->leftJoin('r.book', 'b')->addSelect('b')
            ->leftJoin('b.library', 'l')->addSelect('l')
            ->orderBy('r.createdAt', 'DESC')
            ->addOrderBy('r.id', 'DESC');

        if ($status !== null && $status !== '') {
            $qb->andWhere('r.status = :st')->setParameter('st', $status);
        }

        return $qb->getQuery()->getResult();
    }

//    /**
//     * @return Reservation[] Returns an array of Reservation objects
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

//    public function findOneBySomeField($value): ?Reservation
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
