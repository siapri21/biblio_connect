<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Fusionne l’ancienne table typo « rservation » dans « reservation » et supprime le doublon.
 */
final class Version20260402140100 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Merge rservation into reservation, drop typo table';
    }

    public function up(Schema $schema): void
    {
        // Bases déjà créées avec reservation minimale (id, book_id) seulement
        $this->addSql('ALTER TABLE reservation ADD COLUMN IF NOT EXISTS start_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE reservation ADD COLUMN IF NOT EXISTS end_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE reservation ADD COLUMN IF NOT EXISTS status VARCHAR(255) NOT NULL DEFAULT \'pending\'');
        $this->addSql('ALTER TABLE reservation ADD COLUMN IF NOT EXISTS created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP');

        $this->addSql('DROP TABLE IF EXISTS rservation');
    }

    public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException('Fusion reservation / rservation : retour arrière non supporté.');
    }
}
