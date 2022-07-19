<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220718052519 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE check_list (obj_id VARCHAR(64) NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(32) NOT NULL, boxes JSON DEFAULT NULL, sync_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(obj_id))');
        $this->addSql('CREATE TABLE comment (obj_id VARCHAR(64) NOT NULL, owner_id INT DEFAULT NULL, linked_obj_id VARCHAR(32) NOT NULL, type VARCHAR(32) DEFAULT NULL, images TEXT DEFAULT NULL, tags TEXT DEFAULT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, content TEXT DEFAULT NULL, sync_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(obj_id))');
        $this->addSql('CREATE INDEX IDX_9474526C7E3C61F9 ON comment (owner_id)');
        $this->addSql('COMMENT ON COLUMN comment.images IS \'(DC2Type:simple_array)\'');
        $this->addSql('COMMENT ON COLUMN comment.tags IS \'(DC2Type:simple_array)\'');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C7E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE check_list');
        $this->addSql('DROP TABLE comment');
    }
}
