<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
     * Recherche insensible à la casse sur titre, auteur et catégorie.
     *
     * @return list<Book>
     */
    public function searchCatalog(?string $query, int $limit = 200): array
    {
        $q = trim($query ?? '');
        if ($q === '') {
            return $this->findBy([], ['title' => 'ASC'], $limit);
        }

        // Évite les caractères spéciaux SQL LIKE dans la saisie utilisateur
        $q = str_replace(['%', '_', '\\'], '', $q);
        if ($q === '') {
            return $this->findBy([], ['title' => 'ASC'], $limit);
        }

        $like = '%'.mb_strtolower($q).'%';

        return $this->createQueryBuilder('b')
            ->where('LOWER(b.title) LIKE :term OR LOWER(b.author) LIKE :term OR LOWER(b.category) LIKE :term')
            ->setParameter('term', $like)
            ->orderBy('b.title', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
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
}
