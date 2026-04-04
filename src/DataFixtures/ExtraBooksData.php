<?php

namespace App\DataFixtures;

/**
 * 50 ouvrages — langues variées. Couvertures : Open Library (ISBN).
 *
 * @return list<array{0: string, 1: string, 2: string, 3: string, 4: int, 5: string, 6: string}>
 */
final class ExtraBooksData
{
    public static function books(): array
    {
        return [
            ['Pride and Prejudice', 'Jane Austen', 'Classique', 'en', 3, 'Classic of manners and marriage in Regency England.', '9780141439518'],
            ['The Great Gatsby', 'F. Scott Fitzgerald', 'Roman', 'en', 4, 'Jazz Age America and the American Dream.', '9780743273565'],
            ['To Kill a Mockingbird', 'Harper Lee', 'Roman', 'en', 3, 'Childhood and justice in the American South.', '9780061120084'],
            ['The Catcher in the Rye', 'J.D. Salinger', 'Roman', 'en', 3, 'Adolescence and alienation in New York.', '9780316769488'],
            ['Moby-Dick', 'Herman Melville', 'Aventure', 'en', 2, 'Captain Ahab and the white whale.', '9780142437247'],
            ['The Hobbit', 'J.R.R. Tolkien', 'Fantasy', 'en', 5, 'Bilbo leaves the Shire on a great adventure.', '9780547928241'],
            ['Dune', 'Frank Herbert', 'Science-fiction', 'en', 4, 'Desert planet Arrakis and the spice melange.', '9780441013593'],
            ['The Road', 'Cormac McCarthy', 'Roman', 'en', 2, 'Father and son in a post-apocalyptic world.', '9780307387899'],
            ['Educated', 'Tara Westover', 'Biographie', 'en', 3, 'Memoir of education and family in Idaho.', '9780399590504'],
            ['Becoming', 'Michelle Obama', 'Biographie', 'en', 4, 'Memoir from the former First Lady.', '9781524763138'],
            ['Don Quijote de la Mancha', 'Miguel de Cervantes', 'Classique', 'es', 3, 'Las aventuras del caballero andante.', '9780060933267'],
            ['Cien años de soledad', 'Gabriel García Márquez', 'Roman', 'es', 4, 'Realismo mágico en Macondo.', '9780060883287'],
            ['La sombra del viento', 'Carlos Ruiz Zafón', 'Thriller', 'es', 3, 'Misterio en la Barcelona de posguerra.', '9788408049535'],
            ['Rayuela', 'Julio Cortázar', 'Roman', 'es', 2, 'París y Buenos Aires, capítulos saltables.', '9788466331906'],
            ['Der Process', 'Franz Kafka', 'Classique', 'de', 2, 'Josef K. und das unergründliche Gericht.', '9783518380400'],
            ['Der Herr der Ringe', 'J.R.R. Tolkien', 'Fantasy', 'de', 5, 'Die große Reise nach Mordor.', '9783608939814'],
            ['Siddhartha', 'Hermann Hesse', 'Roman', 'de', 3, 'Suche nach Erleuchtung am Fluss.', '9783551350368'],
            ['Die Verwandlung', 'Franz Kafka', 'Nouvelle', 'de', 4, 'Gregor Samsa erwacht als Ungeziefer.', '9783518220939'],
            ['Il nome della rosa', 'Umberto Eco', 'Policier', 'it', 3, 'Mistero in un monastero medievale.', '9788845929207'],
            ['Il Gattopardo', 'Giuseppe Tomasi di Lampedusa', 'Roman', 'it', 2, 'Il declino della nobiltà siciliana.', '9788845927448'],
            ['Se questo è un uomo', 'Primo Levi', 'Biographie', 'it', 3, 'Testimonianza dagli Lager.', '9788806200300'],
            ['O alquimista', 'Paulo Coelho', 'Roman', 'pt', 5, 'O pastor Santiago e a sua jornada.', '9788595081530'],
            ['Memorial do convento', 'José Saramago', 'Roman', 'pt', 2, 'Portugal no século XVIII.', '9789722119440'],
            ['Crime e castigo', 'Fiódor Dostoiévski', 'Classique', 'pt', 3, 'Raskólnikov e a culpa moral.', '9788525410852'],
            ['Norwegian Wood', 'Haruki Murakami', 'Roman', 'en', 4, 'Love and loss in 1960s Tokyo.', '9780375704024'],
            ['Kafka on the Shore', 'Haruki Murakami', 'Roman', 'en', 3, 'Parallel journeys and strange libraries.', '9781400079278'],
            ['The Kite Runner', 'Khaled Hosseini', 'Roman', 'en', 4, 'Friendship and redemption in Afghanistan.', '9781594631934'],
            ['Life of Pi', 'Yann Martel', 'Aventure', 'en', 3, 'A boy and a tiger on a lifeboat.', '9780156027328'],
            ['The Book Thief', 'Markus Zusak', 'Jeunesse', 'en', 5, 'A girl who steals books in Nazi Germany.', '9780375842207'],
            ['Wool', 'Hugh Howey', 'Science-fiction', 'en', 2, 'Underground silos and a fractured society.', '9780356505379'],
            ['Project Hail Mary', 'Andy Weir', 'Science-fiction', 'en', 3, 'A lone astronaut races to save Earth.', '9780593135204'],
            ['Atomic Habits', 'James Clear', 'Développement personnel', 'en', 6, 'Tiny changes, remarkable results.', '9780735211292'],
            ['Thinking, Fast and Slow', 'Daniel Kahneman', 'Essai', 'en', 3, 'Judgment, decision-making, and bias.', '9780374533557'],
            ['Les Rois maudits I', 'Maurice Druon', 'Historique', 'fr', 3, 'La France des Capétiens.', '9782226281518'],
            ['L\'Amant', 'Marguerite Duras', 'Roman', 'fr', 2, 'Indochine, passion et mémoire.', '9782070378185'],
            ['Stupeur et tremblements', 'Amélie Nothomb', 'Roman', 'fr', 4, 'Une Européenne au Japon.', '9782228889842'],
            ['L\'Élégance du hérisson', 'Muriel Barbery', 'Roman', 'fr', 3, 'Concierge et intellectuelle à Paris.', '9782757841832'],
            ['Paroles', 'Jacques Prévert', 'Poésie', 'fr', 5, 'Poèmes courts et images fortes.', '9782070368967'],
            ['Capitaine Fracasse', 'Théophile Gautier', 'Classique', 'fr', 2, 'Une troupe de comédiens au Grand Siècle.', '9782253002836'],
            ['Bel-Ami', 'Guy de Maupassant', 'Roman', 'fr', 3, 'Montée sociale dans le Paris des journaux.', '9782253000610'],
            ['Germinal', 'Émile Zola', 'Classique', 'fr', 2, 'Grève dans les mines du Nord.', '9782253001201'],
            ['Notre-Dame de Paris', 'Victor Hugo', 'Classique', 'fr', 2, 'Quasimodo, Esmeralda et la cathédrale.', '9782253000832'],
            ['Voyage au centre de la Terre', 'Jules Verne', 'Aventure', 'fr', 4, 'Expédition vers les profondeurs.', '9782253009016'],
            ['Vingt mille lieues sous les mers', 'Jules Verne', 'Aventure', 'fr', 3, 'Le Nautilus et le capitaine Nemo.', '9782253004449'],
            ['Le Tour du monde en quatre-vingts jours', 'Jules Verne', 'Aventure', 'fr', 3, 'Phileas Fogg contre le temps.', '9782253002957'],
            ['La Peste', 'Albert Camus', 'Roman', 'fr', 3, 'Oran frappé par une épidémie.', '9782070370028'],
            ['La Chute', 'Albert Camus', 'Roman', 'fr', 2, 'Confession à Amsterdam.', '9782070367905'],
            ['Madame Bovary', 'Gustave Flaubert', 'Roman', 'fr', 3, 'Emma Bovary et la vie de province.', '9782253002155'],
            ['Le Rouge et le Noir', 'Stendhal', 'Roman', 'fr', 2, 'Julien Sorel et l’ambition sociale.', '9782253000041'],
            ['Les Trois Mousquetaires', 'Alexandre Dumas', 'Aventure', 'fr', 4, 'Tous pour un, un pour tous.', '9782253010248'],
        ];
    }
}
