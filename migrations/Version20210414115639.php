<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210414115639 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE region (id INT AUTO_INCREMENT NOT NULL, job_search_id INT DEFAULT NULL, job_offer_id INT DEFAULT NULL, candidate_id INT DEFAULT NULL, recruiter_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_F62F176A2B78FB8 (job_search_id), INDEX IDX_F62F1763481D195 (job_offer_id), UNIQUE INDEX UNIQ_F62F17691BD8781 (candidate_id), UNIQUE INDEX UNIQ_F62F176156BE243 (recruiter_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE region ADD CONSTRAINT FK_F62F176A2B78FB8 FOREIGN KEY (job_search_id) REFERENCES job_search (id)');
        $this->addSql('ALTER TABLE region ADD CONSTRAINT FK_F62F1763481D195 FOREIGN KEY (job_offer_id) REFERENCES job_offer (id)');
        $this->addSql('ALTER TABLE region ADD CONSTRAINT FK_F62F17691BD8781 FOREIGN KEY (candidate_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE region ADD CONSTRAINT FK_F62F176156BE243 FOREIGN KEY (recruiter_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE department ADD job_search VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE job_search ADD department VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE region');
        $this->addSql('ALTER TABLE department DROP job_search');
        $this->addSql('ALTER TABLE job_search DROP department');
    }
}
