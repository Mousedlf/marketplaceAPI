<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241017080650 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE api ADD client_creation_route VARCHAR(500) NOT NULL DEFAULT \'undefined\'');
        $this->addSql('ALTER TABLE api ADD base_url VARCHAR(500) NOT NULL DEFAULT \'undefined\'');
        $this->addSql('ALTER TABLE api ADD get_requests_route VARCHAR(500) NOT NULL DEFAULT \'undefined\'');
        $this->addSql('ALTER TABLE api ADD revoke_key_route VARCHAR(500) NOT NULL DEFAULT \'undefined\'');
        $this->addSql('ALTER TABLE api ADD generate_new_key VARCHAR(500) NOT NULL DEFAULT \'undefined\'');
        $this->addSql('ALTER TABLE api ADD add_new_requests_route VARCHAR(500) NOT NULL DEFAULT \'undefined\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE api DROP client_creation_route');
        $this->addSql('ALTER TABLE api DROP base_url');
        $this->addSql('ALTER TABLE api DROP get_requests_route');
        $this->addSql('ALTER TABLE api DROP revoke_key_route');
        $this->addSql('ALTER TABLE api DROP generate_new_key');
        $this->addSql('ALTER TABLE api DROP add_new_requests_route');
    }
}
