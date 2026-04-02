<?php

namespace App\Tests\Functional;

use App\Entity\Book;
use App\Entity\Library;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CatalogSearchTest extends WebTestCase
{
    public function testCatalogSearchFiltersByTitle(): void
    {
        $client = static::createClient();
        $em = static::getContainer()->get(EntityManagerInterface::class);

        $library = (new Library())
            ->setName('Biblio search test')
            ->setAddress('Rue X')
            ->setCity('Testville');
        $em->persist($library);
        $em->flush();

        $alpha = (new Book())
            ->setTitle('Alpha Roman')
            ->setAuthor('A. Auteur')
            ->setCategory('Fiction')
            ->setLanguage('fr')
            ->setStock(1)
            ->setLibrary($library);
        $beta = (new Book())
            ->setTitle('Beta Essai')
            ->setAuthor('B. Auteur')
            ->setCategory('Essai')
            ->setLanguage('fr')
            ->setStock(1)
            ->setLibrary($library);
        $em->persist($alpha);
        $em->persist($beta);
        $em->flush();

        $client->request('GET', '/catalogue', ['q' => 'alpha']);
        self::assertResponseIsSuccessful();
        $html = $client->getResponse()->getContent();
        self::assertStringContainsString('Alpha Roman', $html);
        self::assertStringNotContainsString('Beta Essai', $html);
    }
}
