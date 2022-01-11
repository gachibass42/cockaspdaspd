<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220110073146 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE accomodation_id_seq CASCADE');
        $this->addSql('ALTER TABLE accomodation DROP name');
        $this->addSql('ALTER TABLE accomodation ADD CONSTRAINT FK_520D81B3BF396750 FOREIGN KEY (id) REFERENCES milestone (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE accomodation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('ALTER TABLE accomodation DROP CONSTRAINT FK_520D81B3BF396750');
        $this->addSql('ALTER TABLE accomodation ADD name VARCHAR(255) DEFAULT NULL');
    }
}
