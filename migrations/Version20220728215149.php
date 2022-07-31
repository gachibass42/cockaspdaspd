<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220728215149 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE trip_user_role_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE trip_user_role (id INT NOT NULL, trip_id VARCHAR(64) NOT NULL, trip_user_id INT NOT NULL, role_name VARCHAR(16) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_30F787F4A5BC2E0E ON trip_user_role (trip_id)');
        $this->addSql('CREATE INDEX IDX_30F787F491829638 ON trip_user_role (trip_user_id)');
        $this->addSql('ALTER TABLE trip_user_role ADD CONSTRAINT FK_30F787F4A5BC2E0E FOREIGN KEY (trip_id) REFERENCES trip (obj_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE trip_user_role ADD CONSTRAINT FK_30F787F491829638 FOREIGN KEY (trip_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE trip_user_role_id_seq CASCADE');
        $this->addSql('DROP TABLE trip_user_role');
    }
}
