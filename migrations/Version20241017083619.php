<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241017083619 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE api DROP generate_new_key');
        $this->addSql('ALTER TABLE platform_apikey ADD api_id INT NOT NULL default \'5\' ');
        $this->addSql('ALTER TABLE platform_apikey ADD CONSTRAINT FK_52A04CFC54963938 FOREIGN KEY (api_id) REFERENCES api (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_52A04CFC54963938 ON platform_apikey (api_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE api ADD generate_new_key VARCHAR(500) DEFAULT \'undefined\' NOT NULL');
        $this->addSql('ALTER TABLE platform_apikey DROP CONSTRAINT FK_52A04CFC54963938');
        $this->addSql('DROP INDEX IDX_52A04CFC54963938');
        $this->addSql('ALTER TABLE platform_apikey DROP api_id');
    }
}
