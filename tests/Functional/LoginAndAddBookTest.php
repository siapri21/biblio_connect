<?php

namespace App\Tests\Functional;

use App\Entity\Library;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class LoginAndAddBookTest extends WebTestCase
{
    public function testLoginAndAddBook(): void
    {
        $client = static::createClient();
        $container = static::getContainer();
        $em = $container->get(EntityManagerInterface::class);
        $hasher = $container->get(UserPasswordHasherInterface::class);

        $library = (new Library())
            ->setName('Bibliothèque fixture')
            ->setAddress('2 avenue Test')
            ->setCity('Lyon');
        $em->persist($library);
        $em->flush();

        $librarian = (new User())
            ->setEmail('biblio@test.local')
            ->setRoles(['ROLE_LIBRARIAN']);
        $librarian->setPassword($hasher->hashPassword($librarian, 'biblio123!'));
        $em->persist($librarian);
        $em->flush();

        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'biblio@test.local',
            '_password' => 'biblio123!',
        ]);
        $client->submit($form);

        self::assertResponseRedirects('/bibliotheque');
        $client->followRedirect();
        self::assertResponseIsSuccessful();

        $crawler = $client->request('GET', '/admin/catalogue/ouvrage/nouveau');
        self::assertResponseIsSuccessful();

        $form = $crawler->selectButton('Enregistrer')->form([
            'title' => 'Nouveau titre',
            'author' => 'Auteur X',
            'category' => 'SF',
            'language' => 'fr',
            'stock' => '5',
            'library_id' => (string) $library->getId(),
            'description' => 'Résumé',
        ]);
        $client->submit($form);

        self::assertResponseRedirects();
        $client->followRedirect();
        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1.page-title', 'Nouveau titre');
    }
}
