<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241015135251 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE api (id SERIAL NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE offer (id SERIAL NOT NULL, price DOUBLE PRECISION NOT NULL, nb_of_available_requests INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "order" (id SERIAL NOT NULL, total DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE order_api (order_id INT NOT NULL, api_id INT NOT NULL, PRIMARY KEY(order_id, api_id))');
        $this->addSql('CREATE INDEX IDX_EE5E976F8D9F6D38 ON order_api (order_id)');
        $this->addSql('CREATE INDEX IDX_EE5E976F54963938 ON order_api (api_id)');
        $this->addSql('CREATE TABLE platform_apikey (id SERIAL NOT NULL, value VARCHAR(300) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE user_apikey (id SERIAL NOT NULL, nb_used_requests INT NOT NULL, nb_paid_requests INT NOT NULL, active BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE user_apikeys (id SERIAL NOT NULL, api_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E26A052854963938 ON user_apikeys (api_id)');
        $this->addSql('ALTER TABLE order_api ADD CONSTRAINT FK_EE5E976F8D9F6D38 FOREIGN KEY (order_id) REFERENCES "order" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE order_api ADD CONSTRAINT FK_EE5E976F54963938 FOREIGN KEY (api_id) REFERENCES api (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_apikeys ADD CONSTRAINT FK_E26A052854963938 FOREIGN KEY (api_id) REFERENCES api (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE order_api DROP CONSTRAINT FK_EE5E976F8D9F6D38');
        $this->addSql('ALTER TABLE order_api DROP CONSTRAINT FK_EE5E976F54963938');
        $this->addSql('ALTER TABLE user_apikeys DROP CONSTRAINT FK_E26A052854963938');
        $this->addSql('DROP TABLE api');
        $this->addSql('DROP TABLE offer');
        $this->addSql('DROP TABLE "order"');
        $this->addSql('DROP TABLE order_api');
        $this->addSql('DROP TABLE platform_apikey');
        $this->addSql('DROP TABLE user_apikey');
        $this->addSql('DROP TABLE user_apikeys');
    }
}
