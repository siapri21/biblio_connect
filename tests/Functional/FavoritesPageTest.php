<?php

namespace App\Tests\Functional;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class FavoritesPageTest extends WebTestCase
{
    public function testFavoritesRedirectsGuestsToLogin(): void
    {
        $client = static::createClient();
        $client->request('GET', '/favoris');

        self::assertResponseRedirects();
        self::assertStringContainsString('/login', $client->getResponse()->headers->get('Location') ?? '');
    }

    public function testFavoritesPageForLoggedInUser(): void
    {
        $client = static::createClient();
        $container = static::getContainer();
        $em = $container->get(EntityManagerInterface::class);
        $hasher = $container->get(UserPasswordHasherInterface::class);

        $user = (new User())
            ->setEmail('reader-fav@test.local')
            ->setRoles(['ROLE_USER']);
        $user->setPassword($hasher->hashPassword($user, 'reader123!'));
        $em->persist($user);
        $em->flush();

        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'reader-fav@test.local',
            '_password' => 'reader123!',
        ]);
        $client->submit($form);

        $client->request('GET', '/favoris');
        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', 'Mes favoris');
    }
}
