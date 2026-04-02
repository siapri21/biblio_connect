<?php

namespace App\Tests\Functional;

use App\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeAndBookTest extends WebTestCase
{
    public function testLandingAndCatalogAndBookPage(): void
    {
        $client = static::createClient();
        $container = static::getContainer();
        $em = $container->get(EntityManagerInterface::class);

        $book = (new Book())
            ->setTitle('Livre test')
            ->setAuthor('Jean Dupont')
            ->setCategory('Essai')
            ->setLanguage('fr')
            ->setStock(2);
        $em->persist($book);
        $em->flush();

        $client->request('GET', '/');
        self::assertResponseIsSuccessful();
        self::assertStringContainsString('Biblio Connect', $client->getResponse()->getContent());

        $crawler = $client->request('GET', '/catalogue');
        self::assertResponseIsSuccessful();
        self::assertStringContainsString('Livre test', $client->getResponse()->getContent());

        $client->request('GET', '/ouvrage/'.$book->getId());
        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', 'Livre test');
    }
}
