<?php

namespace App\DataFixtures;

use App\Entity\Book;
use App\Entity\Library;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies(): array
    {
        return [UserFixtures::class];
    }

    public function load(ObjectManager $manager): void
    {
        $libraries = [
            ['Bibliothèque municipale — Centre', '12 rue des Livres', 'Paris'],
            ['Bibliothèque de quartier — Nord', '45 avenue des Érables', 'Paris'],
            ['Médiathèque intercommunale', '8 place de la Lecture', 'Lyon'],
        ];
        $libraryEntities = [];
        foreach ($libraries as [$name, $address, $city]) {
            $lib = (new Library())
                ->setName($name)
                ->setAddress($address)
                ->setCity($city);
            $manager->persist($lib);
            $libraryEntities[] = $lib;
        }
        $manager->flush();

        $books = [
            ['Le Petit Prince', 'Antoine de Saint-Exupéry', 'Jeunesse', 'fr', 5, 'Un pilote échoue dans le désert du Sahara et rencontre un petit prince venu d’une autre planète.'],
            ['1984', 'George Orwell', 'Science-fiction', 'fr', 3, 'Une dystopie sur un État totalitaire qui surveille et réécrit l’histoire.'],
            ['L\'Étranger', 'Albert Camus', 'Roman', 'fr', 4, 'Meursault raconte son histoire et son procès après un accident sur une plage.'],
            ['Les Misérables', 'Victor Hugo', 'Classique', 'fr', 2, 'Jean Valjean, Cosette et les barricades de Paris.'],
            ['Harry Potter à l\'école des sorciers', 'J.K. Rowling', 'Fantasy', 'fr', 6, 'Premier tome des aventures du jeune sorcier.'],
            ['Orgueil et Préjugés', 'Jane Austen', 'Romance', 'fr', 3, 'Elizabeth Bennet et Mr Darcy dans l’Angleterre du XIXe siècle.'],
            ['Sapiens', 'Yuval Noah Harari', 'Essai', 'fr', 4, 'Une brève histoire de l’humanité.'],
            ['Le Seigneur des Anneaux', 'J.R.R. Tolkien', 'Fantasy', 'fr', 6, 'La quête de Fondcombe à la Montagne du Destin.'],
            ['Da Vinci Code', 'Dan Brown', 'Policier', 'fr', 5, 'Un thriller autour de symboles et de secrets.'],
            ['L\'Alchimiste', 'Paulo Coelho', 'Roman', 'fr', 4, 'Le berger Santiago part à la recherche d’un trésor.'],
            ['Fondation', 'Isaac Asimov', 'Science-fiction', 'fr', 3, 'La chute de l’Empire galactique et la psychohistoire.'],
            ['L\'Art de la guerre', 'Sun Tzu', 'Essai', 'fr', 2, 'Stratégie et tactique militaires.'],
        ];

        foreach ($books as $index => [$title, $author, $category, $language, $stock, $description]) {
            $coverId = ($index % 6) + 1;
            $coverPath = sprintf('/images/covers/cover-%d.svg', $coverId);
            $library = $libraryEntities[$index % \count($libraryEntities)];

            $book = (new Book())
                ->setTitle($title)
                ->setAuthor($author)
                ->setCategory($category)
                ->setLanguage($language)
                ->setStock($stock)
                ->setDescription($description)
                ->setCoverImagePath($coverPath)
                ->setLibrary($library);

            $manager->persist($book);
        }

        $manager->flush();
    }
}
