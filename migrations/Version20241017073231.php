<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241017073231 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE user_apikeys_id_seq CASCADE');
        $this->addSql('ALTER TABLE user_apikeys DROP CONSTRAINT fk_e26a052854963938');
        $this->addSql('DROP TABLE user_apikeys');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE user_apikeys_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE user_apikeys (id SERIAL NOT NULL, api_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_e26a052854963938 ON user_apikeys (api_id)');
        $this->addSql('ALTER TABLE user_apikeys ADD CONSTRAINT fk_e26a052854963938 FOREIGN KEY (api_id) REFERENCES api (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
