<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220627065343 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE airport_iata_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE airport_iata (id INT NOT NULL, location VARCHAR(64) DEFAULT NULL, city_location VARCHAR(64) DEFAULT NULL, airport_code VARCHAR(3) NOT NULL, country_code VARCHAR(2) DEFAULT NULL, name VARCHAR(1024) DEFAULT NULL, city_name VARCHAR(1024) DEFAULT NULL, international_name VARCHAR(1024) DEFAULT NULL, time_zone VARCHAR(255) DEFAULT NULL, latitude DOUBLE PRECISION DEFAULT NULL, longtitude DOUBLE PRECISION DEFAULT NULL, city_international_name VARCHAR(1024) DEFAULT NULL, city_latitude DOUBLE PRECISION DEFAULT NULL, city_longtitude DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C7B548BF5E9E89CB ON airport_iata (location)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C7B548BFDB46F1AD ON airport_iata (city_location)');
        $this->addSql('ALTER TABLE airport_iata ADD CONSTRAINT FK_C7B548BF5E9E89CB FOREIGN KEY (location) REFERENCES location (obj_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE airport_iata ADD CONSTRAINT FK_C7B548BFDB46F1AD FOREIGN KEY (city_location) REFERENCES location (obj_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE airport_iata_id_seq CASCADE');
        $this->addSql('DROP TABLE airport_iata');
    }
}
