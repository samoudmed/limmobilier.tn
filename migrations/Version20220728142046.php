<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220728142046 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annonces DROP FOREIGN KEY FK_CB988C6FA76ED395');
        $this->addSql('ALTER TABLE favorite DROP FOREIGN KEY FK_68C58ED9A76ED395');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FCD53EDB6');
        $this->addSql('ALTER TABLE photos DROP FOREIGN KEY FK_876E0D9A76ED395');
        $this->addSql('ALTER TABLE rdvs DROP FOREIGN KEY FK_1FC52A01A76ED395');
        $this->addSql('ALTER TABLE reponses DROP FOREIGN KEY FK_1E512EC6F624B39D');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE settings DROP FOREIGN KEY FK_E545A0C5A76ED395');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE rdvs');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE users');
        $this->addSql('ALTER TABLE annonces DROP FOREIGN KEY FK_CB988C6FA76ED395');
        $this->addSql('ALTER TABLE annonces ADD CONSTRAINT FK_CB988C6FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE favorite DROP FOREIGN KEY FK_68C58ED9A76ED395');
        $this->addSql('ALTER TABLE favorite ADD CONSTRAINT FK_68C58ED9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FCD53EDB6');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FCD53EDB6 FOREIGN KEY (receiver_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE photos DROP FOREIGN KEY FK_876E0D98805AB2F');
        $this->addSql('ALTER TABLE photos DROP FOREIGN KEY FK_876E0D9A76ED395');
        $this->addSql('ALTER TABLE photos CHANGE annonce_id annonce_id INT NOT NULL');
        $this->addSql('ALTER TABLE photos ADD CONSTRAINT FK_876E0D98805AB2F FOREIGN KEY (annonce_id) REFERENCES annonces (id)');
        $this->addSql('ALTER TABLE photos ADD CONSTRAINT FK_876E0D9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reponses DROP FOREIGN KEY FK_1E512EC6F624B39D');
        $this->addSql('ALTER TABLE reponses ADD CONSTRAINT FK_1E512EC6F624B39D FOREIGN KEY (sender_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE sender CHANGE nom nom VARCHAR(100) DEFAULT NULL, CHANGE telephone telephone VARCHAR(100) DEFAULT NULL, CHANGE email email VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE settings DROP FOREIGN KEY FK_E545A0C5A76ED395');
        $this->addSql('ALTER TABLE settings CHANGE logo logo VARCHAR(255) DEFAULT NULL, CHANGE agence agence VARCHAR(255) DEFAULT NULL, CHANGE telephone telephone VARCHAR(255) DEFAULT NULL, CHANGE adresse adresse VARCHAR(255) DEFAULT NULL, CHANGE site site VARCHAR(255) DEFAULT NULL, CHANGE facebook facebook VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE settings ADD CONSTRAINT FK_E545A0C5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE traffic CHANGE argv argv VARCHAR(100) DEFAULT NULL, CHANGE argc argc VARCHAR(100) DEFAULT NULL, CHANGE gateway_interface gateway_interface VARCHAR(100) DEFAULT NULL, CHANGE server_addr server_addr VARCHAR(100) DEFAULT NULL, CHANGE server_name server_name VARCHAR(100) DEFAULT NULL, CHANGE server_software server_software VARCHAR(100) DEFAULT NULL, CHANGE server_protocol server_protocol VARCHAR(100) DEFAULT NULL, CHANGE request_method request_method VARCHAR(100) DEFAULT NULL, CHANGE request_time request_time VARCHAR(100) DEFAULT NULL, CHANGE request_time_float request_time_float VARCHAR(100) DEFAULT NULL, CHANGE query_string query_string VARCHAR(100) DEFAULT NULL, CHANGE document_root document_root VARCHAR(100) DEFAULT NULL, CHANGE http_accept http_accept VARCHAR(100) DEFAULT NULL, CHANGE http_accept_charset http_accept_charset VARCHAR(100) DEFAULT NULL, CHANGE http_accept_encoding http_accept_encoding VARCHAR(100) DEFAULT NULL, CHANGE http_accept_language http_accept_language VARCHAR(100) DEFAULT NULL, CHANGE http_connection http_connection VARCHAR(100) DEFAULT NULL, CHANGE http_host http_host VARCHAR(100) DEFAULT NULL, CHANGE http_referer http_referer VARCHAR(100) DEFAULT NULL, CHANGE http_user_agent http_user_agent VARCHAR(100) DEFAULT NULL, CHANGE https https VARCHAR(100) DEFAULT NULL, CHANGE remote_addr remote_addr VARCHAR(100) DEFAULT NULL, CHANGE host_name host_name VARCHAR(100) DEFAULT NULL, CHANGE remote_host remote_host VARCHAR(100) DEFAULT NULL, CHANGE remote_port remote_port VARCHAR(100) DEFAULT NULL, CHANGE remote_user remote_user VARCHAR(100) DEFAULT NULL, CHANGE redirect_remote_user redirect_remote_user VARCHAR(100) DEFAULT NULL, CHANGE script_filename script_filename VARCHAR(100) DEFAULT NULL, CHANGE server_admin server_admin VARCHAR(100) DEFAULT NULL, CHANGE server_port server_port VARCHAR(100) DEFAULT NULL, CHANGE server_signature server_signature VARCHAR(100) DEFAULT NULL, CHANGE path_translated path_translated VARCHAR(100) DEFAULT NULL, CHANGE script_name script_name VARCHAR(100) DEFAULT NULL, CHANGE request_uri request_uri VARCHAR(100) DEFAULT NULL, CHANGE php_auth_digest php_auth_digest VARCHAR(100) DEFAULT NULL, CHANGE php_auth_user php_auth_user VARCHAR(100) DEFAULT NULL, CHANGE php_auth_pw php_auth_pw VARCHAR(100) DEFAULT NULL, CHANGE auth_type auth_type VARCHAR(100) DEFAULT NULL, CHANGE path_info path_info VARCHAR(100) DEFAULT NULL, CHANGE orig_path_info orig_path_info VARCHAR(100) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE favorite DROP FOREIGN KEY FK_68C58ED9A76ED395');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FCD53EDB6');
        $this->addSql('ALTER TABLE photos DROP FOREIGN KEY FK_876E0D9A76ED395');
        $this->addSql('ALTER TABLE reponses DROP FOREIGN KEY FK_1E512EC6F624B39D');
        $this->addSql('ALTER TABLE settings DROP FOREIGN KEY FK_E545A0C5A76ED395');
        $this->addSql('CREATE TABLE rdvs (id INT AUTO_INCREMENT NOT NULL, annonces_id INT DEFAULT NULL, user_id INT DEFAULT NULL, start DATETIME NOT NULL, end DATETIME NOT NULL, statut INT NOT NULL, message LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_1FC52A01A76ED395 (user_id), INDEX IDX_1FC52A014C2885D7 (annonces_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, hashed_token VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, prenom VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, telephone VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, type VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, agence VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, logo VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, ip VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, password VARCHAR(64) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, email VARCHAR(254) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, is_active TINYINT(1) NOT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_1483A5E9450FF010 (telephone), UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE rdvs ADD CONSTRAINT FK_1FC52A014C2885D7 FOREIGN KEY (annonces_id) REFERENCES annonces (id)');
        $this->addSql('ALTER TABLE rdvs ADD CONSTRAINT FK_1FC52A01A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('DROP TABLE user');
        $this->addSql('ALTER TABLE annonces ADD CONSTRAINT FK_CB988C6FA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE favorite DROP FOREIGN KEY FK_68C58ED9A76ED395');
        $this->addSql('ALTER TABLE favorite ADD CONSTRAINT FK_68C58ED9A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FCD53EDB6');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FCD53EDB6 FOREIGN KEY (receiver_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE photos DROP FOREIGN KEY FK_876E0D9A76ED395');
        $this->addSql('ALTER TABLE photos DROP FOREIGN KEY FK_876E0D98805AB2F');
        $this->addSql('ALTER TABLE photos CHANGE annonce_id annonce_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE photos ADD CONSTRAINT FK_876E0D9A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE photos ADD CONSTRAINT FK_876E0D98805AB2F FOREIGN KEY (annonce_id) REFERENCES annonces (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE reponses DROP FOREIGN KEY FK_1E512EC6F624B39D');
        $this->addSql('ALTER TABLE reponses ADD CONSTRAINT FK_1E512EC6F624B39D FOREIGN KEY (sender_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE sender CHANGE nom nom VARCHAR(100) DEFAULT \'NULL\', CHANGE telephone telephone VARCHAR(100) DEFAULT \'NULL\', CHANGE email email VARCHAR(255) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE settings DROP FOREIGN KEY FK_E545A0C5A76ED395');
        $this->addSql('ALTER TABLE settings CHANGE logo logo VARCHAR(255) DEFAULT \'NULL\', CHANGE agence agence VARCHAR(255) DEFAULT \'NULL\', CHANGE telephone telephone VARCHAR(255) DEFAULT \'NULL\', CHANGE adresse adresse VARCHAR(255) DEFAULT \'NULL\', CHANGE site site VARCHAR(255) DEFAULT \'NULL\', CHANGE facebook facebook VARCHAR(255) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE settings ADD CONSTRAINT FK_E545A0C5A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE traffic CHANGE argv argv VARCHAR(100) DEFAULT \'NULL\', CHANGE argc argc VARCHAR(100) DEFAULT \'NULL\', CHANGE gateway_interface gateway_interface VARCHAR(100) DEFAULT \'NULL\', CHANGE server_addr server_addr VARCHAR(100) DEFAULT \'NULL\', CHANGE server_name server_name VARCHAR(100) DEFAULT \'NULL\', CHANGE server_software server_software VARCHAR(100) DEFAULT \'NULL\', CHANGE server_protocol server_protocol VARCHAR(100) DEFAULT \'NULL\', CHANGE request_method request_method VARCHAR(100) DEFAULT \'NULL\', CHANGE request_time request_time VARCHAR(100) DEFAULT \'NULL\', CHANGE request_time_float request_time_float VARCHAR(100) DEFAULT \'NULL\', CHANGE query_string query_string VARCHAR(100) DEFAULT \'NULL\', CHANGE document_root document_root VARCHAR(100) DEFAULT \'NULL\', CHANGE http_accept http_accept VARCHAR(100) DEFAULT \'NULL\', CHANGE http_accept_charset http_accept_charset VARCHAR(100) DEFAULT \'NULL\', CHANGE http_accept_encoding http_accept_encoding VARCHAR(100) DEFAULT \'NULL\', CHANGE http_accept_language http_accept_language VARCHAR(100) DEFAULT \'NULL\', CHANGE http_connection http_connection VARCHAR(100) DEFAULT \'NULL\', CHANGE http_host http_host VARCHAR(100) DEFAULT \'NULL\', CHANGE http_referer http_referer VARCHAR(100) DEFAULT \'NULL\', CHANGE http_user_agent http_user_agent VARCHAR(100) DEFAULT \'NULL\', CHANGE https https VARCHAR(100) DEFAULT \'NULL\', CHANGE remote_addr remote_addr VARCHAR(100) DEFAULT \'NULL\', CHANGE host_name host_name VARCHAR(100) DEFAULT \'NULL\', CHANGE remote_host remote_host VARCHAR(100) DEFAULT \'NULL\', CHANGE remote_port remote_port VARCHAR(100) DEFAULT \'NULL\', CHANGE remote_user remote_user VARCHAR(100) DEFAULT \'NULL\', CHANGE redirect_remote_user redirect_remote_user VARCHAR(100) DEFAULT \'NULL\', CHANGE script_filename script_filename VARCHAR(100) DEFAULT \'NULL\', CHANGE server_admin server_admin VARCHAR(100) DEFAULT \'NULL\', CHANGE server_port server_port VARCHAR(100) DEFAULT \'NULL\', CHANGE server_signature server_signature VARCHAR(100) DEFAULT \'NULL\', CHANGE path_translated path_translated VARCHAR(100) DEFAULT \'NULL\', CHANGE script_name script_name VARCHAR(100) DEFAULT \'NULL\', CHANGE request_uri request_uri VARCHAR(100) DEFAULT \'NULL\', CHANGE php_auth_digest php_auth_digest VARCHAR(100) DEFAULT \'NULL\', CHANGE php_auth_user php_auth_user VARCHAR(100) DEFAULT \'NULL\', CHANGE php_auth_pw php_auth_pw VARCHAR(100) DEFAULT \'NULL\', CHANGE auth_type auth_type VARCHAR(100) DEFAULT \'NULL\', CHANGE path_info path_info VARCHAR(100) DEFAULT \'NULL\', CHANGE orig_path_info orig_path_info VARCHAR(100) DEFAULT \'NULL\'');
    }
}
