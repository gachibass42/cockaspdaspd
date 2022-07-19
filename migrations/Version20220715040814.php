<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220715040814 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE milestone ADD carrier VARCHAR(24) DEFAULT NULL');
        $this->addSql('ALTER TABLE milestone ADD CONSTRAINT FK_4FAC83824739F11C FOREIGN KEY (carrier) REFERENCES airline (obj_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_4FAC83824739F11C ON milestone (carrier)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE milestone DROP CONSTRAINT FK_4FAC83824739F11C');
        $this->addSql('DROP INDEX IDX_4FAC83824739F11C');
        $this->addSql('ALTER TABLE milestone DROP carrier');
    }
}
