<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220608041504 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE location ADD city_location VARCHAR(64) DEFAULT NULL');
        $this->addSql('ALTER TABLE location ADD country_location VARCHAR(64) DEFAULT NULL');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CBDB46F1AD FOREIGN KEY (city_location) REFERENCES location (obj_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CB6D54D876 FOREIGN KEY (country_location) REFERENCES location (obj_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_5E9E89CBDB46F1AD ON location (city_location)');
        $this->addSql('CREATE INDEX IDX_5E9E89CB6D54D876 ON location (country_location)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE location DROP CONSTRAINT FK_5E9E89CBDB46F1AD');
        $this->addSql('ALTER TABLE location DROP CONSTRAINT FK_5E9E89CB6D54D876');
        $this->addSql('DROP INDEX IDX_5E9E89CBDB46F1AD');
        $this->addSql('DROP INDEX IDX_5E9E89CB6D54D876');
        $this->addSql('ALTER TABLE location DROP city_location');
        $this->addSql('ALTER TABLE location DROP country_location');
    }
}
