<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220728151957 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annonces ADD CONSTRAINT FK_CB988C6FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD nom VARCHAR(100) DEFAULT NULL, ADD prenom VARCHAR(100) DEFAULT NULL, ADD telephone VARCHAR(100) DEFAULT NULL, ADD type VARCHAR(100) DEFAULT NULL, ADD agence VARCHAR(100) DEFAULT NULL, ADD logo VARCHAR(100) DEFAULT NULL, ADD ip VARCHAR(100) NOT NULL, ADD is_active TINYINT(1) NOT NULL, ADD is_verified TINYINT(1) NOT NULL, CHANGE roles roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649450FF010 ON user (telephone)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annonces DROP FOREIGN KEY FK_CB988C6FA76ED395');
        $this->addSql('DROP INDEX UNIQ_8D93D649450FF010 ON user');
        $this->addSql('ALTER TABLE user DROP nom, DROP prenom, DROP telephone, DROP type, DROP agence, DROP logo, DROP ip, DROP is_active, DROP is_verified, CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_bin`');
    }
}
