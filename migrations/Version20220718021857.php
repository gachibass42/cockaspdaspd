<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220718021857 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE milestone ALTER milestone_description TYPE TEXT');
        $this->addSql('ALTER TABLE milestone ALTER milestone_description DROP DEFAULT');
        $this->addSql('ALTER TABLE milestone ALTER milestone_description TYPE TEXT');
        $this->addSql('ALTER TABLE milestone ALTER linked_milestones_ids TYPE TEXT');
        $this->addSql('ALTER TABLE milestone ALTER linked_milestones_ids DROP DEFAULT');
        $this->addSql('ALTER TABLE milestone ALTER tags TYPE TEXT');
        $this->addSql('ALTER TABLE milestone ALTER tags DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN milestone.linked_milestones_ids IS \'(DC2Type:simple_array)\'');
        $this->addSql('COMMENT ON COLUMN milestone.tags IS \'(DC2Type:simple_array)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE milestone ALTER milestone_description TYPE VARCHAR(3000)');
        $this->addSql('ALTER TABLE milestone ALTER milestone_description DROP DEFAULT');
        $this->addSql('ALTER TABLE milestone ALTER linked_milestones_ids TYPE TEXT');
        $this->addSql('ALTER TABLE milestone ALTER linked_milestones_ids DROP DEFAULT');
        $this->addSql('ALTER TABLE milestone ALTER tags TYPE TEXT');
        $this->addSql('ALTER TABLE milestone ALTER tags DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN milestone.linked_milestones_ids IS \'(DC2Type:array)\'');
        $this->addSql('COMMENT ON COLUMN milestone.tags IS \'(DC2Type:array)\'');
    }
}
