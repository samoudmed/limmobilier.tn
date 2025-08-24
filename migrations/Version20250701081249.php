<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250701081249 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE banned_ips (ip VARCHAR(45) NOT NULL, banned_at DATETIME NOT NULL, PRIMARY KEY(ip)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE connexion (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_936BF99CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE settings (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, logo VARCHAR(255) DEFAULT NULL, agence VARCHAR(255) DEFAULT NULL, telephone VARCHAR(255) DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, type INT DEFAULT NULL, map LONGTEXT DEFAULT NULL, site VARCHAR(255) DEFAULT NULL, facebook VARCHAR(255) DEFAULT NULL, INDEX IDX_E545A0C5A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE traffic (id INT AUTO_INCREMENT NOT NULL, argv VARCHAR(100) DEFAULT NULL, argc VARCHAR(100) DEFAULT NULL, gateway_interface VARCHAR(100) DEFAULT NULL, server_addr VARCHAR(100) DEFAULT NULL, server_name VARCHAR(100) DEFAULT NULL, server_software VARCHAR(100) DEFAULT NULL, server_protocol VARCHAR(100) DEFAULT NULL, request_method VARCHAR(100) DEFAULT NULL, request_time VARCHAR(100) DEFAULT NULL, request_time_float VARCHAR(100) DEFAULT NULL, query_string VARCHAR(100) DEFAULT NULL, document_root VARCHAR(100) DEFAULT NULL, http_accept VARCHAR(100) DEFAULT NULL, http_accept_charset VARCHAR(100) DEFAULT NULL, http_accept_encoding VARCHAR(100) DEFAULT NULL, http_accept_language VARCHAR(100) DEFAULT NULL, http_connection VARCHAR(100) DEFAULT NULL, http_host VARCHAR(100) DEFAULT NULL, http_referer VARCHAR(100) DEFAULT NULL, http_user_agent VARCHAR(100) DEFAULT NULL, https VARCHAR(100) DEFAULT NULL, remote_addr VARCHAR(100) DEFAULT NULL, host_name VARCHAR(100) DEFAULT NULL, remote_host VARCHAR(100) DEFAULT NULL, remote_port VARCHAR(100) DEFAULT NULL, remote_user VARCHAR(100) DEFAULT NULL, redirect_remote_user VARCHAR(100) DEFAULT NULL, script_filename VARCHAR(100) DEFAULT NULL, server_admin VARCHAR(100) DEFAULT NULL, server_port VARCHAR(100) DEFAULT NULL, server_signature VARCHAR(100) DEFAULT NULL, path_translated VARCHAR(100) DEFAULT NULL, script_name VARCHAR(100) DEFAULT NULL, request_uri VARCHAR(100) DEFAULT NULL, php_auth_digest VARCHAR(100) DEFAULT NULL, php_auth_user VARCHAR(100) DEFAULT NULL, php_auth_pw VARCHAR(100) DEFAULT NULL, auth_type VARCHAR(100) DEFAULT NULL, path_info VARCHAR(100) DEFAULT NULL, orig_path_info VARCHAR(100) DEFAULT NULL, connected_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE connexion ADD CONSTRAINT FK_936BF99CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE settings ADD CONSTRAINT FK_E545A0C5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE annonces CHANGE slug slug VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE annonces ADD CONSTRAINT FK_CB988C6FA73F0036 FOREIGN KEY (ville_id) REFERENCES villes (id)');
        $this->addSql('ALTER TABLE annonces ADD CONSTRAINT FK_CB988C6F75B74E2D FOREIGN KEY (gouvernorat_id) REFERENCES gouvernorat (id)');
        $this->addSql('ALTER TABLE annonces ADD CONSTRAINT FK_CB988C6F56CBBCF5 FOREIGN KEY (delegation_id) REFERENCES delegation (id)');
        $this->addSql('ALTER TABLE annonces ADD CONSTRAINT FK_CB988C6FA6E44244 FOREIGN KEY (pays_id) REFERENCES pays (id)');
        $this->addSql('ALTER TABLE annonces ADD CONSTRAINT FK_CB988C6F30602CA9 FOREIGN KEY (kind_id) REFERENCES kind (id)');
        $this->addSql('ALTER TABLE annonces ADD CONSTRAINT FK_CB988C6FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE delegation CHANGE slug slug VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE delegation ADD CONSTRAINT FK_292F436D75B74E2D FOREIGN KEY (gouvernorat_id) REFERENCES gouvernorat (id)');
        $this->addSql('ALTER TABLE delegation ADD CONSTRAINT FK_292F436DA6E44244 FOREIGN KEY (pays_id) REFERENCES pays (id)');
        $this->addSql('ALTER TABLE favorite ADD CONSTRAINT FK_68C58ED94C2885D7 FOREIGN KEY (annonces_id) REFERENCES annonces (id)');
        $this->addSql('ALTER TABLE favorite ADD CONSTRAINT FK_68C58ED9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE gouvernorat CHANGE slug slug VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE gouvernorat ADD CONSTRAINT FK_4457C12BA6E44244 FOREIGN KEY (pays_id) REFERENCES pays (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FF624B39D FOREIGN KEY (sender_id) REFERENCES sender (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FCD53EDB6 FOREIGN KEY (receiver_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F8805AB2F FOREIGN KEY (annonce_id) REFERENCES annonces (id)');
        $this->addSql('ALTER TABLE newsletter_geo ADD CONSTRAINT FK_9F7BD1D130602CA9 FOREIGN KEY (kind_id) REFERENCES kind (id)');
        $this->addSql('ALTER TABLE newsletter_geo ADD CONSTRAINT FK_9F7BD1D1A73F0036 FOREIGN KEY (ville_id) REFERENCES villes (id)');
        $this->addSql('ALTER TABLE newsletter_geo ADD CONSTRAINT FK_9F7BD1D175B74E2D FOREIGN KEY (gouvernorat_id) REFERENCES gouvernorat (id)');
        $this->addSql('ALTER TABLE newsletter_geo ADD CONSTRAINT FK_9F7BD1D156CBBCF5 FOREIGN KEY (delegation_id) REFERENCES delegation (id)');
        $this->addSql('ALTER TABLE newsletter_geo ADD CONSTRAINT FK_9F7BD1D122DB1917 FOREIGN KEY (newsletter_id) REFERENCES newsletter (id)');
        $this->addSql('ALTER TABLE photos ADD CONSTRAINT FK_876E0D9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE photos ADD CONSTRAINT FK_876E0D98805AB2F FOREIGN KEY (annonce_id) REFERENCES annonces (id)');
        $this->addSql('ALTER TABLE planning ADD CONSTRAINT FK_D499BFF6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE recherche ADD CONSTRAINT FK_B4271B4630602CA9 FOREIGN KEY (kind_id) REFERENCES kind (id)');
        $this->addSql('ALTER TABLE recherche ADD CONSTRAINT FK_B4271B46A73F0036 FOREIGN KEY (ville_id) REFERENCES villes (id)');
        $this->addSql('ALTER TABLE recherche ADD CONSTRAINT FK_B4271B4675B74E2D FOREIGN KEY (gouvernorat_id) REFERENCES gouvernorat (id)');
        $this->addSql('ALTER TABLE recherche ADD CONSTRAINT FK_B4271B4656CBBCF5 FOREIGN KEY (delegation_id) REFERENCES delegation (id)');
        $this->addSql('ALTER TABLE reponses ADD CONSTRAINT FK_1E512EC671FE4C1E FOREIGN KEY (frist_message_id) REFERENCES message (id)');
        $this->addSql('ALTER TABLE reponses ADD CONSTRAINT FK_1E512EC6247A5190 FOREIGN KEY (previous_message_id) REFERENCES message (id)');
        $this->addSql('ALTER TABLE reponses ADD CONSTRAINT FK_1E512EC6F624B39D FOREIGN KEY (sender_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reponses ADD CONSTRAINT FK_1E512EC6CD53EDB6 FOREIGN KEY (receiver_id) REFERENCES sender (id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE types DROP slug');
        $this->addSql('ALTER TABLE user CHANGE nom nom VARCHAR(255) NOT NULL, CHANGE roles roles JSON NOT NULL');
        $this->addSql('ALTER TABLE villes CHANGE slug slug VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE villes ADD CONSTRAINT FK_19209FD856CBBCF5 FOREIGN KEY (delegation_id) REFERENCES delegation (id)');
        $this->addSql('ALTER TABLE villes ADD CONSTRAINT FK_19209FD875B74E2D FOREIGN KEY (gouvernorat_id) REFERENCES gouvernorat (id)');
        $this->addSql('ALTER TABLE villes ADD CONSTRAINT FK_19209FD8A6E44244 FOREIGN KEY (pays_id) REFERENCES pays (id)');
        $this->addSql('ALTER TABLE visite ADD CONSTRAINT FK_B09C8CBBF624B39D FOREIGN KEY (sender_id) REFERENCES sender (id)');
        $this->addSql('ALTER TABLE visite ADD CONSTRAINT FK_B09C8CBB537A1329 FOREIGN KEY (message_id) REFERENCES message (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE connexion DROP FOREIGN KEY FK_936BF99CA76ED395');
        $this->addSql('ALTER TABLE settings DROP FOREIGN KEY FK_E545A0C5A76ED395');
        $this->addSql('DROP TABLE banned_ips');
        $this->addSql('DROP TABLE connexion');
        $this->addSql('DROP TABLE settings');
        $this->addSql('DROP TABLE traffic');
        $this->addSql('ALTER TABLE annonces DROP FOREIGN KEY FK_CB988C6FA73F0036');
        $this->addSql('ALTER TABLE annonces DROP FOREIGN KEY FK_CB988C6F75B74E2D');
        $this->addSql('ALTER TABLE annonces DROP FOREIGN KEY FK_CB988C6F56CBBCF5');
        $this->addSql('ALTER TABLE annonces DROP FOREIGN KEY FK_CB988C6FA6E44244');
        $this->addSql('ALTER TABLE annonces DROP FOREIGN KEY FK_CB988C6F30602CA9');
        $this->addSql('ALTER TABLE annonces DROP FOREIGN KEY FK_CB988C6FA76ED395');
        $this->addSql('ALTER TABLE annonces CHANGE slug slug VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE delegation DROP FOREIGN KEY FK_292F436D75B74E2D');
        $this->addSql('ALTER TABLE delegation DROP FOREIGN KEY FK_292F436DA6E44244');
        $this->addSql('ALTER TABLE delegation CHANGE slug slug VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE favorite DROP FOREIGN KEY FK_68C58ED94C2885D7');
        $this->addSql('ALTER TABLE favorite DROP FOREIGN KEY FK_68C58ED9A76ED395');
        $this->addSql('ALTER TABLE gouvernorat DROP FOREIGN KEY FK_4457C12BA6E44244');
        $this->addSql('ALTER TABLE gouvernorat CHANGE slug slug VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FF624B39D');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FCD53EDB6');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F8805AB2F');
        $this->addSql('ALTER TABLE newsletter_geo DROP FOREIGN KEY FK_9F7BD1D130602CA9');
        $this->addSql('ALTER TABLE newsletter_geo DROP FOREIGN KEY FK_9F7BD1D1A73F0036');
        $this->addSql('ALTER TABLE newsletter_geo DROP FOREIGN KEY FK_9F7BD1D175B74E2D');
        $this->addSql('ALTER TABLE newsletter_geo DROP FOREIGN KEY FK_9F7BD1D156CBBCF5');
        $this->addSql('ALTER TABLE newsletter_geo DROP FOREIGN KEY FK_9F7BD1D122DB1917');
        $this->addSql('ALTER TABLE photos DROP FOREIGN KEY FK_876E0D9A76ED395');
        $this->addSql('ALTER TABLE photos DROP FOREIGN KEY FK_876E0D98805AB2F');
        $this->addSql('ALTER TABLE planning DROP FOREIGN KEY FK_D499BFF6A76ED395');
        $this->addSql('ALTER TABLE recherche DROP FOREIGN KEY FK_B4271B4630602CA9');
        $this->addSql('ALTER TABLE recherche DROP FOREIGN KEY FK_B4271B46A73F0036');
        $this->addSql('ALTER TABLE recherche DROP FOREIGN KEY FK_B4271B4675B74E2D');
        $this->addSql('ALTER TABLE recherche DROP FOREIGN KEY FK_B4271B4656CBBCF5');
        $this->addSql('ALTER TABLE reponses DROP FOREIGN KEY FK_1E512EC671FE4C1E');
        $this->addSql('ALTER TABLE reponses DROP FOREIGN KEY FK_1E512EC6247A5190');
        $this->addSql('ALTER TABLE reponses DROP FOREIGN KEY FK_1E512EC6F624B39D');
        $this->addSql('ALTER TABLE reponses DROP FOREIGN KEY FK_1E512EC6CD53EDB6');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE types ADD slug VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE nom nom VARCHAR(100) DEFAULT NULL, CHANGE roles roles JSON NOT NULL');
        $this->addSql('ALTER TABLE villes DROP FOREIGN KEY FK_19209FD856CBBCF5');
        $this->addSql('ALTER TABLE villes DROP FOREIGN KEY FK_19209FD875B74E2D');
        $this->addSql('ALTER TABLE villes DROP FOREIGN KEY FK_19209FD8A6E44244');
        $this->addSql('ALTER TABLE villes CHANGE slug slug VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE visite DROP FOREIGN KEY FK_B09C8CBBF624B39D');
        $this->addSql('ALTER TABLE visite DROP FOREIGN KEY FK_B09C8CBB537A1329');
    }
}
