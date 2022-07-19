<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220715040723 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE milestone (obj_id VARCHAR(64) NOT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, type VARCHAR(64) NOT NULL, milestone_description VARCHAR(3000) DEFAULT NULL, milestone_order INT DEFAULT NULL, user_edited BOOLEAN NOT NULL, journey_number VARCHAR(255) DEFAULT NULL, seat VARCHAR(20) DEFAULT NULL, vagon INT DEFAULT NULL, class_type VARCHAR(32) DEFAULT NULL, terminal VARCHAR(255) DEFAULT NULL, distance DOUBLE PRECISION DEFAULT NULL, aircraft VARCHAR(255) DEFAULT NULL, duration VARCHAR(255) DEFAULT NULL, address VARCHAR(3000) DEFAULT NULL, website VARCHAR(255) DEFAULT NULL, phone_number VARCHAR(32) DEFAULT NULL, is_start_point BOOLEAN NOT NULL, is_end_point BOOLEAN NOT NULL, in_transit BOOLEAN NOT NULL, rent_type VARCHAR(32) DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, linked_milestones_ids TEXT DEFAULT NULL, meal_timetables JSON DEFAULT NULL, PRIMARY KEY(obj_id))');
        $this->addSql('COMMENT ON COLUMN milestone.linked_milestones_ids IS \'(DC2Type:array)\'');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EC141EF866093344 ON airline (obj_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE milestone');
        $this->addSql('DROP INDEX UNIQ_EC141EF866093344');
    }
}
