<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220110052035 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "user" ADD phone VARCHAR(20) DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD description VARCHAR(512) DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD is_guide BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD name VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "user" DROP phone');
        $this->addSql('ALTER TABLE "user" DROP description');
        $this->addSql('ALTER TABLE "user" DROP is_guide');
        $this->addSql('ALTER TABLE "user" DROP name');
    }
}
