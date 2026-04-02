<?php

namespace App\Tests\Functional;

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

        $form = $crawler->filter('form')->form([
            'title' => 'Nouveau titre',
            'author' => 'Auteur X',
            'category' => 'SF',
            'language' => 'fr',
            'stock' => '5',
            'description' => 'Résumé',
        ]);
        $client->submit($form);

        self::assertResponseRedirects();
        $client->followRedirect();
        self::assertSelectorTextContains('h1', 'Nouveau titre');
    }
}
