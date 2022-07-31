<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220722000936 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX uniq_ec141ef8bf396750');
        $this->addSql('ALTER TABLE airline RENAME COLUMN id TO guid');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EC141EF82B6FCFB2 ON airline (guid)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX UNIQ_EC141EF82B6FCFB2');
        $this->addSql('ALTER TABLE airline RENAME COLUMN guid TO id');
        $this->addSql('CREATE UNIQUE INDEX uniq_ec141ef8bf396750 ON airline (id)');
    }
}
