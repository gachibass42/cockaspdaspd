<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220707015055 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE airport_iata ADD longitude DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE airport_iata ADD city_longitude DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE airport_iata DROP longtitude');
        $this->addSql('ALTER TABLE airport_iata DROP city_longtitude');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE airport_iata ADD longtitude DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE airport_iata ADD city_longtitude DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE airport_iata DROP longitude');
        $this->addSql('ALTER TABLE airport_iata DROP city_longitude');
    }
}
