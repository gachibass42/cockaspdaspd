<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220721234732 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX uniq_ec141ef866093344 CASCADE');
        $this->addSql('ALTER TABLE airline DROP CONSTRAINT airline_pkey CASCADE');
        $this->addSql('ALTER TABLE airline ALTER obj_id SET NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EC141EF8BF396750 ON airline (id)');
        $this->addSql('ALTER TABLE airline ADD PRIMARY KEY (obj_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX UNIQ_EC141EF8BF396750');
        $this->addSql('DROP INDEX airline_pkey');
        $this->addSql('ALTER TABLE airline ALTER obj_id DROP NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX uniq_ec141ef866093344 ON airline (obj_id)');
        $this->addSql('ALTER TABLE airline ADD PRIMARY KEY (id)');
    }
}
