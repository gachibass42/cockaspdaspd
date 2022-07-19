<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220714061719 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE accomodation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('ALTER TABLE accomodation DROP CONSTRAINT FK_520D81B3BF396750');
        $this->addSql('ALTER TABLE accomodation ADD name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE flight DROP CONSTRAINT FK_C257E60EBF396750');
        $this->addSql('ALTER TABLE movement DROP CONSTRAINT FK_F4DD95F7BF396750');
        $this->addSql('DROP SEQUENCE accomodation_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE milestone_id_seq CASCADE');
        $this->addSql('DROP TABLE accomodation');
        $this->addSql('DROP TABLE flight');
        $this->addSql('DROP TABLE milestone');
        $this->addSql('DROP TABLE movement');
        $this->addSql('ALTER TABLE trip_user DROP CONSTRAINT FK_A6AB4522A5BC2E0E');
        $this->addSql('DROP SEQUENCE trip_id_seq CASCADE');
        $this->addSql('DROP TABLE trip');
        $this->addSql('DROP TABLE trip_user');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
