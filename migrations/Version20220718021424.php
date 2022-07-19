<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220718021424 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trip ALTER trip_description TYPE TEXT');
        $this->addSql('ALTER TABLE trip ALTER trip_description DROP DEFAULT');
        $this->addSql('ALTER TABLE trip ALTER trip_description TYPE TEXT');
        $this->addSql('ALTER TABLE trip ALTER milestones_ids TYPE TEXT');
        $this->addSql('ALTER TABLE trip ALTER milestones_ids DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN trip.milestones_ids IS \'(DC2Type:simple_array)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE trip ALTER trip_description TYPE VARCHAR(1024)');
        $this->addSql('ALTER TABLE trip ALTER trip_description DROP DEFAULT');
        $this->addSql('ALTER TABLE trip ALTER milestones_ids TYPE TEXT');
        $this->addSql('ALTER TABLE trip ALTER milestones_ids DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN trip.milestones_ids IS \'(DC2Type:array)\'');
    }
}
