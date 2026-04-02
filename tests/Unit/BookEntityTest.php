<?php

namespace App\Tests\Unit;

use App\Entity\Book;
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
}
