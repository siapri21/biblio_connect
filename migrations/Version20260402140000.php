<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Colonne User::isVerified (email verification SymfonyCasts).
 */
final class Version20260402140000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add user.is_verified for email verification';
    }

    public function up(Schema $schema): void
    {
        // IF NOT EXISTS : peut être relancé si la colonne a été ajoutée à la main (Neon / SQL).
        $this->addSql('ALTER TABLE "user" ADD COLUMN IF NOT EXISTS is_verified BOOLEAN NOT NULL DEFAULT false');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE "user" DROP COLUMN IF EXISTS is_verified');
    }
}
