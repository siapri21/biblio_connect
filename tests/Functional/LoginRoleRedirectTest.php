<?php

namespace App\Tests\Functional;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Challenge : après connexion, destination selon le rôle (voir LoginRedirectSubscriber + LoginTargetResolver).
 */
class LoginRoleRedirectTest extends WebTestCase
{
    public function testAdminRedirectsToAdminDashboard(): void
    {
        $client = static::createClient();
        $this->createUser('admin-redirect@test.local', ['ROLE_ADMIN'], 'AdminRedirect!2026');

        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'admin-redirect@test.local',
            '_password' => 'AdminRedirect!2026',
        ]);
        $client->submit($form);

        self::assertResponseRedirects('/admin');
        $client->followRedirect();
        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', 'Pilotage');
    }

    public function testUsagerRedirectsToUserSpace(): void
    {
        $client = static::createClient();
        $this->createUser('usager-redirect@test.local', [], 'UserRedirect!2026');

        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'usager-redirect@test.local',
            '_password' => 'UserRedirect!2026',
        ]);
        $client->submit($form);

        self::assertResponseRedirects('/espace');
        $client->followRedirect();
        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', 'Mon espace');
    }

    /**
     * @param list<string> $roles
     */
    private function createUser(string $email, array $roles, string $plainPassword): void
    {
        $container = static::getContainer();
        $em = $container->get(EntityManagerInterface::class);
        $hasher = $container->get(UserPasswordHasherInterface::class);

        $user = (new User())
            ->setEmail($email)
            ->setRoles($roles)
            ->setIsVerified(true);
        $user->setPassword($hasher->hashPassword($user, $plainPassword));
        $em->persist($user);
        $em->flush();
    }
}
