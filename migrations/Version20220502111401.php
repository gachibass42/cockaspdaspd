<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220502111401 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE location_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE location (id INT NOT NULL, city_location_id INT DEFAULT NULL, country_location_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, lat DOUBLE PRECISION NOT NULL, lon DOUBLE PRECISION NOT NULL, description VARCHAR(255) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, time_zone VARCHAR(32) DEFAULT NULL, code_iata VARCHAR(3) DEFAULT NULL, country_code VARCHAR(3) DEFAULT NULL, external_place_id VARCHAR(32) DEFAULT NULL, search_tags TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5E9E89CB25F93F81 ON location (city_location_id)');
        $this->addSql('CREATE INDEX IDX_5E9E89CB13DA0B45 ON location (country_location_id)');
        $this->addSql('CREATE INDEX ix_location_external_place_id ON location (external_place_id)');
        $this->addSql('CREATE INDEX ix_location_search_tags ON location (search_tags)');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CB25F93F81 FOREIGN KEY (city_location_id) REFERENCES location (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CB13DA0B45 FOREIGN KEY (country_location_id) REFERENCES location (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE location DROP CONSTRAINT FK_5E9E89CB25F93F81');
        $this->addSql('ALTER TABLE location DROP CONSTRAINT FK_5E9E89CB13DA0B45');
        $this->addSql('DROP SEQUENCE location_id_seq CASCADE');
        $this->addSql('DROP TABLE location');
    }
}
