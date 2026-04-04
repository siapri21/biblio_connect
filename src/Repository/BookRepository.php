<?php

namespace App\Repository;

use App\Entity\Book;
use App\Entity\Reservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\ParameterType;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    /**
     * Recherche sur titre, auteur, catégorie + filtres (bibliothèque, dispo, tri).
     *
     * @return list<Book>
     */
    public function searchCatalog(
        ?string $query,
        ?string $category = null,
        ?string $author = null,
        ?string $language = null,
        ?int $libraryId = null,
        ?bool $availableOnly = null,
        string $sort = 'title_asc',
        int $limit = 200,
    ): array {
        $qb = $this->createQueryBuilder('b')
            ->setMaxResults($limit);

        $q = trim($query ?? '');
        if ($q !== '') {
            $q = str_replace(['%', '_', '\\'], '', $q);
            if ($q !== '') {
                $like = '%'.mb_strtolower($q).'%';
                $qb->andWhere('LOWER(b.title) LIKE :term OR LOWER(b.author) LIKE :term OR LOWER(b.category) LIKE :term')
                    ->setParameter('term', $like);
            }
        }

        $cat = $category !== null ? trim($category) : '';
        if ($cat !== '') {
            $qb->andWhere('b.category = :cat')->setParameter('cat', $cat);
        }

        $auth = $author !== null ? trim($author) : '';
        if ($auth !== '') {
            $qb->andWhere('b.author = :auth')->setParameter('auth', $auth);
        }

        $lang = $language !== null ? trim($language) : '';
        if ($lang !== '') {
            $qb->andWhere('b.language = :lang')->setParameter('lang', $lang);
        }

        if ($libraryId !== null && $libraryId > 0) {
            $qb->leftJoin('b.library', 'libf')
                ->andWhere('libf.id = :lid')
                ->setParameter('lid', $libraryId);
        }

        if ($availableOnly === true) {
            $qb->andWhere('b.stock > (
                SELECT COUNT(rsub.id) FROM '.Reservation::class.' rsub
                WHERE rsub.book = b AND rsub.status IN (:rst)
            )')->setParameter('rst', ['pending', 'confirmed']);
        }

        match ($sort) {
            'title_desc' => $qb->orderBy('b.title', 'DESC'),
            'author_asc' => $qb->orderBy('b.author', 'ASC')->addOrderBy('b.title', 'ASC'),
            'author_desc' => $qb->orderBy('b.author', 'DESC')->addOrderBy('b.title', 'ASC'),
            default => $qb->orderBy('b.title', 'ASC'),
        };

        return $qb->getQuery()->getResult();
    }

    /**
     * @return list<array{book: Book, count: int}>
     */
    public function findTopBooksByReservationVolume(int $limit = 5): array
    {
        $rows = $this->getEntityManager()->getConnection()->fetchAllAssociative(
            'SELECT b.id, COUNT(r.id) AS c FROM book b LEFT JOIN reservation r ON r.book_id = b.id GROUP BY b.id ORDER BY c DESC, b.title ASC LIMIT ?',
            [$limit],
            [ParameterType::INTEGER],
        );
        $out = [];
        foreach ($rows as $row) {
            $book = $this->find((int) $row['id']);
            if ($book instanceof Book) {
                $out[] = ['book' => $book, 'count' => (int) $row['c']];
            }
        }

        return $out;
    }

    /**
     * @return list<string>
     */
    public function findDistinctCategories(): array
    {
        return $this->createQueryBuilder('b')
            ->select('b.category')
            ->distinct()
            ->orderBy('b.category', 'ASC')
            ->getQuery()
            ->getSingleColumnResult();
    }

    /**
     * @return list<string>
     */
    public function findDistinctAuthors(): array
    {
        return $this->createQueryBuilder('b')
            ->select('b.author')
            ->distinct()
            ->orderBy('b.author', 'ASC')
            ->setMaxResults(80)
            ->getQuery()
            ->getSingleColumnResult();
    }

    /**
     * @return list<string>
     */
    public function findDistinctLanguages(): array
    {
        return $this->createQueryBuilder('b')
            ->select('b.language')
            ->distinct()
            ->orderBy('b.language', 'ASC')
            ->getQuery()
            ->getSingleColumnResult();
    }

    /**
     * Dernières entrées pour la page d’accueil.
     *
     * @return list<Book>
     */
    public function findFeaturedForHome(int $limit = 8): array
    {
        return $this->createQueryBuilder('b')
            ->orderBy('b.id', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function countDistinctCategories(): int
    {
        return (int) $this->createQueryBuilder('b')
            ->select('COUNT(DISTINCT b.category)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
