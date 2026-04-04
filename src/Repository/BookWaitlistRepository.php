<?php

namespace App\Repository;

use App\Entity\Book;
use App\Entity\BookWaitlist;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BookWaitlist>
 */
class BookWaitlistRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BookWaitlist::class);
    }

    public function findOneByMemberAndBook(User $member, Book $book): ?BookWaitlist
    {
        return $this->findOneBy(['member' => $member, 'book' => $book]);
    }

    /**
     * Position dans la file (1 = premier).
     */
    public function getQueuePosition(BookWaitlist $entry): int
    {
        $qb = $this->createQueryBuilder('w')
            ->select('COUNT(w.id)')
            ->andWhere('w.book = :book')
            ->setParameter('book', $entry->getBook());

        if ($entry->getId() !== null) {
            $qb->andWhere('w.createdAt < :ca OR (w.createdAt = :ca AND w.id < :wid)')
                ->setParameter('ca', $entry->getCreatedAt())
                ->setParameter('wid', $entry->getId());
        } else {
            $qb->andWhere('w.createdAt < :ca')->setParameter('ca', $entry->getCreatedAt());
        }

        return 1 + (int) $qb->getQuery()->getSingleScalarResult();
    }

    public function countByBook(Book $book): int
    {
        return (int) $this->createQueryBuilder('w')
            ->select('COUNT(w.id)')
            ->andWhere('w.book = :b')
            ->setParameter('b', $book)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
