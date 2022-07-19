<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220715073506 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE trip (obj_id VARCHAR(64) NOT NULL, owner_id INT NOT NULL, name VARCHAR(255) DEFAULT NULL, start_date DATE NOT NULL, end_date DATE NOT NULL, locked BOOLEAN NOT NULL, duration INT DEFAULT NULL, trip_description VARCHAR(1024) DEFAULT NULL, tags VARCHAR(1024) DEFAULT NULL, milestones_ids TEXT DEFAULT NULL, visibility BOOLEAN NOT NULL, sync_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(obj_id))');
        $this->addSql('CREATE INDEX IDX_7656F53B7E3C61F9 ON trip (owner_id)');
        $this->addSql('COMMENT ON COLUMN trip.milestones_ids IS \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE trip ADD CONSTRAINT FK_7656F53B7E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE milestone ADD owner_id INT NOT NULL');
        $this->addSql('ALTER TABLE milestone ADD sync_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE milestone ADD visibility BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE milestone ADD CONSTRAINT FK_4FAC83827E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_4FAC83827E3C61F9 ON milestone (owner_id)');
        $this->addSql('ALTER TABLE "user" ADD last_successful_sync_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD last_sync_try_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE trip');
        $this->addSql('ALTER TABLE "user" DROP last_successful_sync_date');
        $this->addSql('ALTER TABLE "user" DROP last_sync_try_date');
        $this->addSql('ALTER TABLE milestone DROP CONSTRAINT FK_4FAC83827E3C61F9');
        $this->addSql('DROP INDEX IDX_4FAC83827E3C61F9');
        $this->addSql('ALTER TABLE milestone DROP owner_id');
        $this->addSql('ALTER TABLE milestone DROP sync_date');
        $this->addSql('ALTER TABLE milestone DROP visibility');
    }
}
