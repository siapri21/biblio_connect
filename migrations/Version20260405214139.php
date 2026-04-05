<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260405214139 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add user.name for display (greeting)';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER INDEX idx_cbe5a331537732b0 RENAME TO IDX_CBE5A331FE2541D7');
        $this->addSql('ALTER INDEX idx_9f74a28f16a2b381 RENAME TO IDX_B1AC297116A2B381');
        $this->addSql('ALTER INDEX idx_9f74a28f7597d3fe RENAME TO IDX_B1AC29717597D3FE');
        $this->addSql('ALTER TABLE reservation DROP CONSTRAINT fk_42c8495516a2b381');
        $this->addSql('ALTER TABLE reservation ALTER start_at DROP DEFAULT');
        $this->addSql('ALTER TABLE reservation ALTER end_at DROP DEFAULT');
        $this->addSql('ALTER TABLE reservation ALTER status DROP DEFAULT');
        $this->addSql('ALTER TABLE reservation ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE reservation ALTER extension_count DROP DEFAULT');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495516A2B381 FOREIGN KEY (book_id) REFERENCES book (id) ON DELETE CASCADE NOT DEFERRABLE');
        $this->addSql('ALTER INDEX idx_42c84955a76ed395 RENAME TO IDX_42C849557597D3FE');
        $this->addSql('ALTER TABLE "user" ADD name VARCHAR(120) DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ALTER is_verified DROP DEFAULT');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER INDEX idx_cbe5a331fe2541d7 RENAME TO idx_cbe5a331537732b0');
        $this->addSql('ALTER INDEX idx_b1ac29717597d3fe RENAME TO idx_9f74a28f7597d3fe');
        $this->addSql('ALTER INDEX idx_b1ac297116a2b381 RENAME TO idx_9f74a28f16a2b381');
        $this->addSql('ALTER TABLE reservation DROP CONSTRAINT FK_42C8495516A2B381');
        $this->addSql('ALTER TABLE reservation ALTER start_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE reservation ALTER end_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE reservation ALTER status SET DEFAULT \'pending\'');
        $this->addSql('ALTER TABLE reservation ALTER created_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE reservation ALTER extension_count SET DEFAULT 0');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT fk_42c8495516a2b381 FOREIGN KEY (book_id) REFERENCES book (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER INDEX idx_42c849557597d3fe RENAME TO idx_42c84955a76ed395');
        $this->addSql('ALTER TABLE "user" DROP name');
        $this->addSql('ALTER TABLE "user" ALTER is_verified SET DEFAULT false');
    }
}
