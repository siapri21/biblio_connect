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

        /**
         * Couvertures : Open Library (ISBN) — chargées depuis le réseau en consultation.
         * [ titre, auteur, catégorie, langue, stock, description, url_couverture ou null pour SVG local ]
         */
        $books = [
            ['Le Petit Prince', 'Antoine de Saint-Exupéry', 'Jeunesse', 'fr', 5, 'Un pilote échoue dans le désert du Sahara et rencontre un petit prince venu d’une autre planète.', 'https://covers.openlibrary.org/b/isbn/9782070612758-L.jpg'],
            ['1984', 'George Orwell', 'Science-fiction', 'fr', 3, 'Une dystopie sur un État totalitaire qui surveille et réécrit l’histoire.', 'https://covers.openlibrary.org/b/isbn/9780141036144-L.jpg'],
            ['L\'Étranger', 'Albert Camus', 'Roman', 'fr', 4, 'Meursault raconte son histoire et son procès après un accident sur une plage.', 'https://covers.openlibrary.org/b/isbn/9782070360021-L.jpg'],
            ['Les Misérables', 'Victor Hugo', 'Classique', 'fr', 2, 'Jean Valjean, Cosette et les barricades de Paris.', 'https://covers.openlibrary.org/b/isbn/9782253096339-L.jpg'],
            ['Harry Potter à l\'école des sorciers', 'J.K. Rowling', 'Fantasy', 'fr', 6, 'Premier tome des aventures du jeune sorcier.', 'https://covers.openlibrary.org/b/isbn/9782070543588-L.jpg'],
            ['Orgueil et Préjugés', 'Jane Austen', 'Romance', 'fr', 3, 'Elizabeth Bennet et Mr Darcy dans l’Angleterre du XIXe siècle.', 'https://covers.openlibrary.org/b/isbn/9782267022602-L.jpg'],
            ['Sapiens', 'Yuval Noah Harari', 'Essai', 'fr', 4, 'Une brève histoire de l’humanité.', 'https://covers.openlibrary.org/b/isbn/9782226318198-L.jpg'],
            ['Le Seigneur des Anneaux', 'J.R.R. Tolkien', 'Fantasy', 'fr', 6, 'La Communauté de l’Anneau et la quête vers la Montagne du Destin.', 'https://covers.openlibrary.org/b/isbn/9782267027003-L.jpg'],
            ['Da Vinci Code', 'Dan Brown', 'Policier', 'fr', 5, 'Un thriller autour de symboles, d’énigmes et de secrets.', 'https://covers.openlibrary.org/b/isbn/9782709626300-L.jpg'],
            ['L\'Alchimiste', 'Paulo Coelho', 'Roman', 'fr', 4, 'Le berger Santiago part à la recherche d’un trésor.', 'https://covers.openlibrary.org/b/isbn/9782290003197-L.jpg'],
            ['Fondation', 'Isaac Asimov', 'Science-fiction', 'fr', 3, 'La chute de l’Empire galactique et la psychohistoire.', 'https://covers.openlibrary.org/b/isbn/9782070360533-L.jpg'],
            ['L\'Art de la guerre', 'Sun Tzu', 'Essai', 'fr', 2, 'Stratégie et tactique.', 'https://covers.openlibrary.org/b/isbn/9782268077824-L.jpg'],
            ['Le Comte de Monte-Cristo', 'Alexandre Dumas', 'Classique', 'fr', 3, 'Edmond Dantès, trahison et vengeance.', 'https://covers.openlibrary.org/b/isbn/9782253005837-L.jpg'],
            ['Astérix le Gaulois', 'Goscinny & Uderzo', 'Bande dessinée', 'fr', 8, 'Le village gaulois résiste encore et toujours à l’occupant.', 'https://covers.openlibrary.org/b/isbn/9782012105753-L.jpg'],
            ['Les Aventures de Sherlock Holmes', 'Arthur Conan Doyle', 'Policier', 'fr', 4, 'Enquêtes du détective londonien au tournant du siècle.', 'https://covers.openlibrary.org/b/isbn/9782266234432-L.jpg'],
            ['Les Fleurs du mal', 'Charles Baudelaire', 'Poésie', 'fr', 3, 'Recueil emblématique de la modernité poétique.', 'https://covers.openlibrary.org/b/isbn/9782070407610-L.jpg'],
            ['De la Terre à la Lune', 'Jules Verne', 'Aventure', 'fr', 3, 'Le canon de Tampa et le rêve d’aller sur la Lune.', 'https://covers.openlibrary.org/b/isbn/9782253009484-L.jpg'],
            ['Gone Girl', 'Gillian Flynn', 'Thriller', 'fr', 2, 'Disparition et médias : les apparences sont trompeuses.', 'https://covers.openlibrary.org/b/isbn/9782253194623-L.jpg'],
            ['Hunger Games', 'Suzanne Collins', 'Jeunesse', 'fr', 5, 'Panem, arène et révolte dans un futur dystopique.', 'https://covers.openlibrary.org/b/isbn/9782266277057-L.jpg'],
            ['Une brève histoire du temps', 'Stephen Hawking', 'Sciences', 'fr', 3, 'Cosmologie accessible : du Big Bang aux trous noirs.', 'https://covers.openlibrary.org/b/isbn/9782228885833-L.jpg'],
            ['Changer l\'eau des fleurs', 'Valérie Perrin', 'Roman', 'fr', 4, 'Deuil, mémoire et rencontres au cimetière.', 'https://covers.openlibrary.org/b/isbn/9782253257773-L.jpg'],
            ['Cinquante nuances de Grey', 'E.L. James', 'Romance', 'en', 2, 'Roman contemporain à succès (version originale anglaise).', 'https://covers.openlibrary.org/b/isbn/9780345803481-L.jpg'],
        ];

        foreach ($books as $index => $row) {
            [$title, $author, $category, $language, $stock, $description, $cover] = $row;
            $library = $libraryEntities[$index % \count($libraryEntities)];
            if ($cover === null) {
                $coverId = ($index % 6) + 1;
                $cover = sprintf('/images/covers/cover-%d.svg', $coverId);
            }

            $book = (new Book())
                ->setTitle($title)
                ->setAuthor($author)
                ->setCategory($category)
                ->setLanguage($language)
                ->setStock($stock)
                ->setDescription($description)
                ->setCoverImagePath($cover)
                ->setLibrary($library);

            $manager->persist($book);
        }

        $extraRows = array_merge(ExtraBooksData::books(), SecondBatchBooksData::books());
        foreach ($extraRows as $idx => $row) {
            [$title, $author, $category, $language, $stock, $description, $isbn] = $row;
            $library = $libraryEntities[($idx + 22) % \count($libraryEntities)];
            $isbnClean = preg_replace('/[^0-9X]/i', '', (string) $isbn);
            $cover = sprintf('https://covers.openlibrary.org/b/isbn/%s-L.jpg', $isbnClean);

            $book = (new Book())
                ->setTitle($title)
                ->setAuthor($author)
                ->setCategory($category)
                ->setLanguage($language)
                ->setStock($stock)
                ->setDescription($description)
                ->setCoverImagePath($cover)
                ->setLibrary($library);

            $manager->persist($book);
        }

        $manager->flush();
    }
}
