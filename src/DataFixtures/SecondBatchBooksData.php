<?php

namespace App\DataFixtures;

/**
 * Ouvrages supplémentaires (lot 2) — couvertures Open Library (ISBN).
 *
 * @return list<array{0: string, 1: string, 2: string, 3: string, 4: int, 5: string, 6: string}>
 */
final class SecondBatchBooksData
{
    public static function books(): array
    {
        return [
            ['The Midnight Library', 'Matt Haig', 'Roman', 'en', 4, 'Between life and death, a library of possible lives.', '9780525559498'],
            ['Klara and the Sun', 'Kazuo Ishiguro', 'Science-fiction', 'en', 3, 'An artificial friend observes the human world.', '9780593318171'],
            ['Never Let Me Go', 'Kazuo Ishiguro', 'Roman', 'en', 3, 'Friendship and fate at a secluded English school.', '9780307386476'],
            ['The Remains of the Day', 'Kazuo Ishiguro', 'Roman', 'en', 2, 'A butler reflects on duty and missed love.', '9780679731726'],
            ['A Gentleman in Moscow', 'Amor Towles', 'Roman', 'en', 3, 'A count under house arrest in a Moscow hotel.', '9780670026190'],
            ['The Song of Achilles', 'Madeline Miller', 'Roman', 'en', 5, 'Patroclus and Achilles in the Trojan War.', '9780062060624'],
            ['Circe', 'Madeline Miller', 'Roman', 'en', 4, 'The witch of Aeaea reimagined.', '9780316427209'],
            ['The Silent Patient', 'Alex Michaelides', 'Thriller', 'en', 3, 'A therapist and a painter who stopped speaking.', '9781250301697'],
            ['Where the Crawdads Sing', 'Delia Owens', 'Roman', 'en', 4, 'Marsh girl, mystery, and the North Carolina coast.', '9780735219090'],
            ['Normal People', 'Sally Rooney', 'Roman', 'en', 3, 'Connell and Marianne from school to adulthood.', '9780571334650'],
            ['The Thursday Murder Club', 'Richard Osman', 'Policier', 'en', 4, 'Retirement home sleuths in Kent.', '9780241425445'],
            ['American Gods', 'Neil Gaiman', 'Fantasy', 'en', 4, 'Old gods and new on a road trip across America.', '9780062572079'],
            ['Good Omens', 'Terry Pratchett & Neil Gaiman', 'Roman', 'en', 3, 'An angel and a demon save the world.', '9780060853983'],
            ['The Name of the Wind', 'Patrick Rothfuss', 'Fantasy', 'en', 4, 'Kvothe’s legend begins.', '9780756404741'],
            ['Mistborn: The Final Empire', 'Brandon Sanderson', 'Fantasy', 'en', 3, 'A thief joins a rebellion in a world of ash.', '9780765350370'],
            ['Station Eleven', 'Emily St. John Mandel', 'Science-fiction', 'en', 3, 'A travelling symphony after a pandemic.', '9780804172448'],
            ['The Handmaid\'s Tale', 'Margaret Atwood', 'Science-fiction', 'en', 4, 'Gilead and Offred’s resistance.', '9780385490818'],
            ['Rebecca', 'Daphne du Maurier', 'Roman', 'en', 3, 'Manderley, memory, and Mrs Danvers.', '9780380730407'],
            ['The Seven Husbands of Evelyn Hugo', 'Taylor Jenkins Reid', 'Roman', 'en', 3, 'A Hollywood icon’s final interview.', '9781501161938'],
            ['Mexican Gothic', 'Silvia Moreno-Garcia', 'Thriller', 'en', 2, 'A decaying mansion in the Mexican mountains.', '9780525620792'],
            ['Hyperion', 'Dan Simmons', 'Science-fiction', 'en', 3, 'Pilgrims on the world of the Time Tombs.', '9780553283686'],
            ['The Blade Itself', 'Joe Abercrombie', 'Fantasy', 'en', 2, 'First book of The First Law.', '9780316020210'],
            ['Shoe Dog', 'Phil Knight', 'Biographie', 'en', 3, 'Nike and the early running years.', '9781501135910'],
            ['Barbarian Days', 'William Finnegan', 'Biographie', 'en', 2, 'Surfing and a life on the waves.', '9780143108583'],
            ['The Ride of a Lifetime', 'Robert Iger', 'Biographie', 'en', 2, 'Lessons from leading Disney.', '9780399592096'],
            ['Les Années', 'Annie Ernaux', 'Roman', 'fr', 2, 'Mémoire collective et intime.', '9782070124535'],
            ['L\'Anomalie', 'Hervé Le Tellier', 'Roman', 'fr', 3, 'Vol Paris–New York et bifurcation du réel.', '9782072856599'],
            ['Le consentement', 'Vanessa Springora', 'Essai', 'fr', 2, 'Récit et réflexion sur une relation abusive.', '9782072823997'],
            ['Le Petit Nicolas', 'René Goscinny', 'Jeunesse', 'fr', 6, 'La bande de copains et l’école.', '9782070611361'],
            ['Tout le bleu du ciel', 'Mélissa Da Costa', 'Roman', 'fr', 3, 'Voyage intérieur et deuil.', '9782253234292'],
            ['Chanson douce', 'Leïla Slimani', 'Thriller', 'fr', 3, 'Une nounou, une famille, un drame.', '9782072738955'],
            ['L\'Art de perdre', 'Alice Zeniter', 'Roman', 'fr', 2, 'Mémoire familiale et histoire algérienne.', '9782072734124'],
            ['En attendant Bojangles', 'Olivier Bourdeaut', 'Roman', 'fr', 3, 'Une famille hors du temps.', '9782072863175'],
            ['Le Grand Livre de la forêt', 'Peter Wohlleben', 'Nature', 'fr', 3, 'Ce que les arbres nous apprennent.', '9782330066478'],
            ['El amor en los tiempos del cólera', 'Gabriel García Márquez', 'Roman', 'es', 3, 'Amor y espera a lo largo de la vida.', '9788497592451'],
            ['Pedro Páramo', 'Juan Rulfo', 'Roman', 'es', 2, 'Realismo mágico en Comala.', '9788437604183'],
            ['Die Blechtrommel', 'Günter Grass', 'Roman', 'de', 2, 'Danzig und die Geschichte des Jahrhunderts.', '9783518389115'],
            ['Der Schwarm', 'Frank Schätzing', 'Thriller', 'de', 3, 'Ozeane, Wesen und Geheimnis.', '9783462036748'],
            ['Il fu Mattia Pascal', 'Luigi Pirandello', 'Roman', 'it', 2, 'Identità e caso nella vita del protagonista.', '9788807900762'],
            ['Il sistema periodico', 'Primo Levi', 'Essai', 'it', 3, 'Elementi chimici e memoria.', '9780141188729'],
            ['Os Maias', 'Eça de Queirós', 'Roman', 'pt', 2, 'Lisboa e a decadência da aristocracia.', '9789720040487'],
            ['Dom Casmurro', 'Machado de Assis', 'Roman', 'pt', 3, 'Capitu, Bentinho e a dúvida.', '9788535906884'],
            ['The Dispossessed', 'Ursula K. Le Guin', 'Science-fiction', 'en', 2, 'Anarres, Urras, and a physicist between worlds.', '9780061054884'],
            ['The Left Hand of Darkness', 'Ursula K. Le Guin', 'Science-fiction', 'en', 3, 'Winter, Gethen, and first contact.', '9780441478125'],
            ['Foundation and Empire', 'Isaac Asimov', 'Science-fiction', 'en', 2, 'The Foundation faces the Empire.', '9780553293372'],
            ['The Priory of the Orange Tree', 'Samantha Shannon', 'Fantasy', 'en', 2, 'Queens, dragons, and an ancient threat.', '9781408883469'],
            ['Der Richter und sein Henker', 'Friedrich Dürrenmatt', 'Policier', 'de', 3, 'Kriminalgeschichte in der Schweiz.', '9783257232118'],
            ['Buddenbrooks', 'Thomas Mann', 'Roman', 'de', 2, 'Verfall einer Kaufmannsfamilie.', '9783518382178'],
            ['Il deserto dei Tartari', 'Dino Buzzati', 'Roman', 'it', 2, 'L’attesa al fortezza.', '9788845921232'],
            ['The Nightingale', 'Kristin Hannah', 'Historique', 'en', 2, 'Two sisters in occupied France.', '9780312577223'],
            ['Oryx and Crake', 'Margaret Atwood', 'Science-fiction', 'en', 2, 'Snowman and the world before the flood.', '9780385721677'],
            ['Immune', 'Philipp Dettmer', 'Sciences', 'en', 3, 'How the immune system defends you.', '9780593241312'],
            ['Braiding Sweetgrass', 'Robin Wall Kimmerer', 'Essai', 'en', 2, 'Indigenous wisdom and botany.', '9781571313560'],
            ['The Man Who Died Twice', 'Richard Osman', 'Policier', 'en', 3, 'Second case for the Thursday Murder Club.', '9780241988268'],
            ['The Bullet That Missed', 'Richard Osman', 'Policier', 'en', 3, 'Third case for the retirement sleuths.', '9780241988234'],
            ['Des hommes', 'Laurent Mauvignier', 'Roman', 'fr', 2, 'Un village, une guerre, des vies.', '9782707348234'],
            ['Un soir au club', 'Christian Bobin', 'Poésie', 'fr', 3, 'Fragments et méditation.', '9782267029234'],
            ['La Horde du contrevent', 'Alain Damasio', 'Science-fiction', 'fr', 2, 'Vents, quête et monde hostile.', '9782070468669'],
            ['Los detectives salvajes', 'Roberto Bolaño', 'Roman', 'es', 3, 'Artistas, desierto y búsqueda en México.', '9788433969405'],
            ['El laberinto de los espíritus', 'Carlos Ruiz Zafón', 'Thriller', 'es', 3, 'Cierre del ciclo del Cementerio de los libros.', '9788408169458'],
            ['Der Steppenwolf', 'Hermann Hesse', 'Roman', 'de', 3, 'Harry Haller entre l’homme et le loup.', '9783518380380'],
            ['The Goldfinch', 'Donna Tartt', 'Roman', 'en', 3, 'A boy, a painting, and a life upturned.', '9780316055444'],
        ];
    }
}
