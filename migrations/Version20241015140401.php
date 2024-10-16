<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241015140401 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE api ADD created_by_id INT NOT NULL');
        $this->addSql('ALTER TABLE api ADD name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE api ADD description TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE api ADD CONSTRAINT FK_AD05D80FB03A8386 FOREIGN KEY (created_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_AD05D80FB03A8386 ON api (created_by_id)');
        $this->addSql('ALTER TABLE "order" ADD by_user_id INT NOT NULL');
        $this->addSql('ALTER TABLE "order" ADD CONSTRAINT FK_F5299398DC9C2434 FOREIGN KEY (by_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_F5299398DC9C2434 ON "order" (by_user_id)');
        $this->addSql('ALTER TABLE user_apikey ADD of_user_id INT NOT NULL');
        $this->addSql('ALTER TABLE user_apikey ADD CONSTRAINT FK_DD154C9C5A1B2224 FOREIGN KEY (of_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_DD154C9C5A1B2224 ON user_apikey (of_user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "order" DROP CONSTRAINT FK_F5299398DC9C2434');
        $this->addSql('DROP INDEX IDX_F5299398DC9C2434');
        $this->addSql('ALTER TABLE "order" DROP by_user_id');
        $this->addSql('ALTER TABLE user_apikey DROP CONSTRAINT FK_DD154C9C5A1B2224');
        $this->addSql('DROP INDEX IDX_DD154C9C5A1B2224');
        $this->addSql('ALTER TABLE user_apikey DROP of_user_id');
        $this->addSql('ALTER TABLE api DROP CONSTRAINT FK_AD05D80FB03A8386');
        $this->addSql('DROP INDEX IDX_AD05D80FB03A8386');
        $this->addSql('ALTER TABLE api DROP created_by_id');
        $this->addSql('ALTER TABLE api DROP name');
        $this->addSql('ALTER TABLE api DROP description');
    }
}
