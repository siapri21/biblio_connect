<?php

namespace App\Tests\Unit;

use App\Entity\Book;
use App\Entity\Library;
use PHPUnit\Framework\TestCase;

class BookEntityTest extends TestCase
{
    public function testCreateBook(): void
    {
        $book = (new Book())
            ->setTitle('Test')
            ->setAuthor('Auteur')
            ->setCategory('Roman')
            ->setLanguage('fr')
            ->setStock(3);

        self::assertSame('Test', $book->getTitle());
        self::assertSame(3, $book->getStock());
    }

    /** Fiche détaillée : rattachement bibliothèque + couverture (unitaire, sans persistance). */
    public function testCreateBookWithLibraryCoverAndDescription(): void
    {
        $library = (new Library())
            ->setName('Bibliothèque centrale')
            ->setAddress('1 rue du Livre')
            ->setCity('Paris');

        $book = (new Book())
            ->setTitle('Atlas des mondes perdus')
            ->setAuthor('Claire Nord')
            ->setCategory('Fantasy')
            ->setLanguage('fr')
            ->setStock(4)
            ->setDescription('Un voyage cartographique à travers les légendes.')
            ->setCoverImagePath('/images/covers/cover-3.svg')
            ->setLibrary($library);

        self::assertSame($library, $book->getLibrary());
        self::assertSame('/images/covers/cover-3.svg', $book->getCoverImagePath());
        self::assertStringContainsString('cartographique', (string) $book->getDescription());
    }
}
