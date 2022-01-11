<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220110072150 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE accomodation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE milestone_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE accomodation (id INT NOT NULL, name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE flight (id INT NOT NULL, gate_number INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE milestone (id INT NOT NULL, trip_id INT NOT NULL, name VARCHAR(255) NOT NULL, start_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, finish_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, dtype VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_4FAC8382A5BC2E0E ON milestone (trip_id)');
        $this->addSql('CREATE TABLE movement (id INT NOT NULL, voyage_id VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE flight ADD CONSTRAINT FK_C257E60EBF396750 FOREIGN KEY (id) REFERENCES milestone (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE milestone ADD CONSTRAINT FK_4FAC8382A5BC2E0E FOREIGN KEY (trip_id) REFERENCES trip (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE movement ADD CONSTRAINT FK_F4DD95F7BF396750 FOREIGN KEY (id) REFERENCES milestone (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE flight DROP CONSTRAINT FK_C257E60EBF396750');
        $this->addSql('ALTER TABLE movement DROP CONSTRAINT FK_F4DD95F7BF396750');
        $this->addSql('DROP SEQUENCE accomodation_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE milestone_id_seq CASCADE');
        $this->addSql('DROP TABLE accomodation');
        $this->addSql('DROP TABLE flight');
        $this->addSql('DROP TABLE milestone');
        $this->addSql('DROP TABLE movement');
    }
}
