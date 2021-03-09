<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210307202008 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE business_profile (id INT AUTO_INCREMENT NOT NULL, wording VARCHAR(150) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cause (id INT AUTO_INCREMENT NOT NULL, text VARCHAR(150) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contract_type (id INT AUTO_INCREMENT NOT NULL, wording VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE country (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, code VARCHAR(10) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE development_goals (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, url VARCHAR(150) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE job_offer (id INT AUTO_INCREMENT NOT NULL, contract_type_id INT NOT NULL, level_experience_id INT NOT NULL, level_study_id INT DEFAULT NULL, country_id INT NOT NULL, entity_id INT NOT NULL, category_id INT DEFAULT NULL, remuneration_id INT NOT NULL, title VARCHAR(150) NOT NULL, description LONGTEXT NOT NULL, tags LONGTEXT DEFAULT NULL, city VARCHAR(150) NOT NULL, creation_date DATETIME NOT NULL, published TINYINT(1) NOT NULL, INDEX IDX_288A3A4ECD1DF15B (contract_type_id), INDEX IDX_288A3A4EED2D18A0 (level_experience_id), INDEX IDX_288A3A4E6A19A639 (level_study_id), INDEX IDX_288A3A4EF92F3E70 (country_id), INDEX IDX_288A3A4E81257D5D (entity_id), INDEX IDX_288A3A4E12469DE2 (category_id), INDEX IDX_288A3A4E1D7E2A02 (remuneration_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE job_search (id INT AUTO_INCREMENT NOT NULL, contract_type_id INT DEFAULT NULL, country_id INT DEFAULT NULL, cause_id INT DEFAULT NULL, desired_remuneration_id INT DEFAULT NULL, indemnity VARCHAR(150) DEFAULT NULL, region VARCHAR(150) DEFAULT NULL, city VARCHAR(150) DEFAULT NULL, job_title VARCHAR(150) DEFAULT NULL, tags LONGTEXT DEFAULT NULL, INDEX IDX_E4F4F626CD1DF15B (contract_type_id), INDEX IDX_E4F4F626F92F3E70 (country_id), INDEX IDX_E4F4F62666E2221E (cause_id), INDEX IDX_E4F4F6267B58CB7E (desired_remuneration_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE legal_status (id INT AUTO_INCREMENT NOT NULL, text VARCHAR(100) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE level_experience (id INT AUTO_INCREMENT NOT NULL, wording VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE level_study (id INT AUTO_INCREMENT NOT NULL, wording VARCHAR(150) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE picture (id INT AUTO_INCREMENT NOT NULL, picture_name VARCHAR(150) DEFAULT NULL, picture_size INT DEFAULT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recognition (id INT AUTO_INCREMENT NOT NULL, cause_id INT NOT NULL, recruiter_id INT NOT NULL, description LONGTEXT NOT NULL, INDEX IDX_95DB6FB266E2221E (cause_id), INDEX IDX_95DB6FB2156BE243 (recruiter_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE remuneration (id INT AUTO_INCREMENT NOT NULL, text VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE scoring (id INT AUTO_INCREMENT NOT NULL, social INT NOT NULL, environment INT NOT NULL, economy INT NOT NULL, societal INT NOT NULL, choices LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', greatest_value VARCHAR(20) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE social_network (id INT AUTO_INCREMENT NOT NULL, web_site VARCHAR(150) DEFAULT NULL, linkedin VARCHAR(150) DEFAULT NULL, stack_overflow VARCHAR(150) DEFAULT NULL, github VARCHAR(150) DEFAULT NULL, facebook VARCHAR(150) DEFAULT NULL, twitter VARCHAR(150) DEFAULT NULL, skype VARCHAR(150) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, scoring_id INT DEFAULT NULL, picture_id INT DEFAULT NULL, level_study_id INT DEFAULT NULL, level_experience_id INT DEFAULT NULL, business_profile_id INT DEFAULT NULL, job_search_id INT DEFAULT NULL, authorized_country_id INT DEFAULT NULL, social_network_id INT DEFAULT NULL, legal_status_id INT DEFAULT NULL, main_cause_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(100) NOT NULL, lastname VARCHAR(100) NOT NULL, phone VARCHAR(255) NOT NULL, registration_date DATE NOT NULL, about LONGTEXT DEFAULT NULL, is_verified TINYINT(1) NOT NULL, private TINYINT(1) NOT NULL, typeUser VARCHAR(255) NOT NULL, cv VARCHAR(150) DEFAULT NULL, current_role VARCHAR(150) DEFAULT NULL, inspiration LONGTEXT DEFAULT NULL, entity_name VARCHAR(150) DEFAULT NULL, reason_to_be LONGTEXT DEFAULT NULL, carbon_foot_print VARCHAR(150) DEFAULT NULL, activity_number VARCHAR(50) DEFAULT NULL, function VARCHAR(150) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D649DF2EDCBF (scoring_id), INDEX IDX_8D93D649EE45BDBF (picture_id), INDEX IDX_8D93D6496A19A639 (level_study_id), INDEX IDX_8D93D649ED2D18A0 (level_experience_id), INDEX IDX_8D93D649C591A13 (business_profile_id), INDEX IDX_8D93D649A2B78FB8 (job_search_id), INDEX IDX_8D93D649105DE7BE (authorized_country_id), INDEX IDX_8D93D649FA413953 (social_network_id), INDEX IDX_8D93D649873E3FEC (legal_status_id), INDEX IDX_8D93D649BFC0336B (main_cause_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recruiter_cause (recruiter_id INT NOT NULL, cause_id INT NOT NULL, INDEX IDX_2D2B3EEA156BE243 (recruiter_id), INDEX IDX_2D2B3EEA66E2221E (cause_id), PRIMARY KEY(recruiter_id, cause_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE job_offer ADD CONSTRAINT FK_288A3A4ECD1DF15B FOREIGN KEY (contract_type_id) REFERENCES contract_type (id)');
        $this->addSql('ALTER TABLE job_offer ADD CONSTRAINT FK_288A3A4EED2D18A0 FOREIGN KEY (level_experience_id) REFERENCES level_experience (id)');
        $this->addSql('ALTER TABLE job_offer ADD CONSTRAINT FK_288A3A4E6A19A639 FOREIGN KEY (level_study_id) REFERENCES level_study (id)');
        $this->addSql('ALTER TABLE job_offer ADD CONSTRAINT FK_288A3A4EF92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)');
        $this->addSql('ALTER TABLE job_offer ADD CONSTRAINT FK_288A3A4E81257D5D FOREIGN KEY (entity_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE job_offer ADD CONSTRAINT FK_288A3A4E12469DE2 FOREIGN KEY (category_id) REFERENCES business_profile (id)');
        $this->addSql('ALTER TABLE job_offer ADD CONSTRAINT FK_288A3A4E1D7E2A02 FOREIGN KEY (remuneration_id) REFERENCES remuneration (id)');
        $this->addSql('ALTER TABLE job_search ADD CONSTRAINT FK_E4F4F626CD1DF15B FOREIGN KEY (contract_type_id) REFERENCES contract_type (id)');
        $this->addSql('ALTER TABLE job_search ADD CONSTRAINT FK_E4F4F626F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)');
        $this->addSql('ALTER TABLE job_search ADD CONSTRAINT FK_E4F4F62666E2221E FOREIGN KEY (cause_id) REFERENCES cause (id)');
        $this->addSql('ALTER TABLE job_search ADD CONSTRAINT FK_E4F4F6267B58CB7E FOREIGN KEY (desired_remuneration_id) REFERENCES remuneration (id)');
        $this->addSql('ALTER TABLE recognition ADD CONSTRAINT FK_95DB6FB266E2221E FOREIGN KEY (cause_id) REFERENCES cause (id)');
        $this->addSql('ALTER TABLE recognition ADD CONSTRAINT FK_95DB6FB2156BE243 FOREIGN KEY (recruiter_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649DF2EDCBF FOREIGN KEY (scoring_id) REFERENCES scoring (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649EE45BDBF FOREIGN KEY (picture_id) REFERENCES picture (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6496A19A639 FOREIGN KEY (level_study_id) REFERENCES level_study (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649ED2D18A0 FOREIGN KEY (level_experience_id) REFERENCES level_experience (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649C591A13 FOREIGN KEY (business_profile_id) REFERENCES business_profile (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649A2B78FB8 FOREIGN KEY (job_search_id) REFERENCES job_search (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649105DE7BE FOREIGN KEY (authorized_country_id) REFERENCES country (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649FA413953 FOREIGN KEY (social_network_id) REFERENCES social_network (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649873E3FEC FOREIGN KEY (legal_status_id) REFERENCES legal_status (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649BFC0336B FOREIGN KEY (main_cause_id) REFERENCES cause (id)');
        $this->addSql('ALTER TABLE recruiter_cause ADD CONSTRAINT FK_2D2B3EEA156BE243 FOREIGN KEY (recruiter_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recruiter_cause ADD CONSTRAINT FK_2D2B3EEA66E2221E FOREIGN KEY (cause_id) REFERENCES cause (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE job_offer DROP FOREIGN KEY FK_288A3A4E12469DE2');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649C591A13');
        $this->addSql('ALTER TABLE job_search DROP FOREIGN KEY FK_E4F4F62666E2221E');
        $this->addSql('ALTER TABLE recognition DROP FOREIGN KEY FK_95DB6FB266E2221E');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649BFC0336B');
        $this->addSql('ALTER TABLE recruiter_cause DROP FOREIGN KEY FK_2D2B3EEA66E2221E');
        $this->addSql('ALTER TABLE job_offer DROP FOREIGN KEY FK_288A3A4ECD1DF15B');
        $this->addSql('ALTER TABLE job_search DROP FOREIGN KEY FK_E4F4F626CD1DF15B');
        $this->addSql('ALTER TABLE job_offer DROP FOREIGN KEY FK_288A3A4EF92F3E70');
        $this->addSql('ALTER TABLE job_search DROP FOREIGN KEY FK_E4F4F626F92F3E70');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649105DE7BE');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649A2B78FB8');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649873E3FEC');
        $this->addSql('ALTER TABLE job_offer DROP FOREIGN KEY FK_288A3A4EED2D18A0');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649ED2D18A0');
        $this->addSql('ALTER TABLE job_offer DROP FOREIGN KEY FK_288A3A4E6A19A639');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6496A19A639');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649EE45BDBF');
        $this->addSql('ALTER TABLE job_offer DROP FOREIGN KEY FK_288A3A4E1D7E2A02');
        $this->addSql('ALTER TABLE job_search DROP FOREIGN KEY FK_E4F4F6267B58CB7E');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649DF2EDCBF');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649FA413953');
        $this->addSql('ALTER TABLE job_offer DROP FOREIGN KEY FK_288A3A4E81257D5D');
        $this->addSql('ALTER TABLE recognition DROP FOREIGN KEY FK_95DB6FB2156BE243');
        $this->addSql('ALTER TABLE recruiter_cause DROP FOREIGN KEY FK_2D2B3EEA156BE243');
        $this->addSql('DROP TABLE business_profile');
        $this->addSql('DROP TABLE cause');
        $this->addSql('DROP TABLE contract_type');
        $this->addSql('DROP TABLE country');
        $this->addSql('DROP TABLE development_goals');
        $this->addSql('DROP TABLE job_offer');
        $this->addSql('DROP TABLE job_search');
        $this->addSql('DROP TABLE legal_status');
        $this->addSql('DROP TABLE level_experience');
        $this->addSql('DROP TABLE level_study');
        $this->addSql('DROP TABLE picture');
        $this->addSql('DROP TABLE recognition');
        $this->addSql('DROP TABLE remuneration');
        $this->addSql('DROP TABLE scoring');
        $this->addSql('DROP TABLE social_network');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE recruiter_cause');
    }
}
