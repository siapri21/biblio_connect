<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Comptes de démonstration (dev / tests manuels).
 *
 * Après chargement des fixtures :
 * - bibliothecaire@biblio-connect.local / Biblio!demo2026
 * - admin@biblio-connect.local / Admin!demo2026
 * - usager@biblio-connect.local / User!demo2026
 *
 * Ne pas utiliser ces mots de passe en production.
 */
final class UserFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $librarian = (new User())
            ->setEmail('bibliothecaire@biblio-connect.local')
            ->setName('Camille Dupont')
            ->setRoles(['ROLE_LIBRARIAN'])
            ->setIsVerified(true);
        $librarian->setPassword($this->passwordHasher->hashPassword($librarian, 'Biblio!demo2026'));

        $admin = (new User())
            ->setEmail('admin@biblio-connect.local')
            ->setName('Alex Martin')
            ->setRoles(['ROLE_ADMIN'])
            ->setIsVerified(true);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'Admin!demo2026'));

        $reader = (new User())
            ->setEmail('usager@biblio-connect.local')
            ->setRoles([])
            ->setIsVerified(true);
        $reader->setPassword($this->passwordHasher->hashPassword($reader, 'User!demo2026'));

        $manager->persist($librarian);
        $manager->persist($admin);
        $manager->persist($reader);
        $manager->flush();
    }
}
