<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220110053121 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE trip_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE trip (id INT NOT NULL, name VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, start_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, finish_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE trip_user (trip_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(trip_id, user_id))');
        $this->addSql('CREATE INDEX IDX_A6AB4522A5BC2E0E ON trip_user (trip_id)');
        $this->addSql('CREATE INDEX IDX_A6AB4522A76ED395 ON trip_user (user_id)');
        $this->addSql('ALTER TABLE trip_user ADD CONSTRAINT FK_A6AB4522A5BC2E0E FOREIGN KEY (trip_id) REFERENCES trip (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE trip_user ADD CONSTRAINT FK_A6AB4522A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE trip_user DROP CONSTRAINT FK_A6AB4522A5BC2E0E');
        $this->addSql('DROP SEQUENCE trip_id_seq CASCADE');
        $this->addSql('DROP TABLE trip');
        $this->addSql('DROP TABLE trip_user');
    }
}
