<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210408115929 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cv DROP FOREIGN KEY FK_B66FFE92AA17481D');
        $this->addSql('DROP INDEX IDX_B66FFE92AA17481D ON cv');
        $this->addSql('ALTER TABLE cv DROP favorite_id');
        $this->addSql('ALTER TABLE favorite ADD cv_id INT DEFAULT NULL, ADD job_offer_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE favorite ADD CONSTRAINT FK_68C58ED9CFE419E2 FOREIGN KEY (cv_id) REFERENCES cv (id)');
        $this->addSql('ALTER TABLE favorite ADD CONSTRAINT FK_68C58ED93481D195 FOREIGN KEY (job_offer_id) REFERENCES job_offer (id)');
        $this->addSql('CREATE INDEX IDX_68C58ED9CFE419E2 ON favorite (cv_id)');
        $this->addSql('CREATE INDEX IDX_68C58ED93481D195 ON favorite (job_offer_id)');
        $this->addSql('ALTER TABLE job_offer DROP FOREIGN KEY FK_288A3A4EAA17481D');
        $this->addSql('DROP INDEX IDX_288A3A4EAA17481D ON job_offer');
        $this->addSql('ALTER TABLE job_offer DROP favorite_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cv ADD favorite_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE cv ADD CONSTRAINT FK_B66FFE92AA17481D FOREIGN KEY (favorite_id) REFERENCES favorite (id)');
        $this->addSql('CREATE INDEX IDX_B66FFE92AA17481D ON cv (favorite_id)');
        $this->addSql('ALTER TABLE favorite DROP FOREIGN KEY FK_68C58ED9CFE419E2');
        $this->addSql('ALTER TABLE favorite DROP FOREIGN KEY FK_68C58ED93481D195');
        $this->addSql('DROP INDEX IDX_68C58ED9CFE419E2 ON favorite');
        $this->addSql('DROP INDEX IDX_68C58ED93481D195 ON favorite');
        $this->addSql('ALTER TABLE favorite DROP cv_id, DROP job_offer_id');
        $this->addSql('ALTER TABLE job_offer ADD favorite_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE job_offer ADD CONSTRAINT FK_288A3A4EAA17481D FOREIGN KEY (favorite_id) REFERENCES favorite (id)');
        $this->addSql('CREATE INDEX IDX_288A3A4EAA17481D ON job_offer (favorite_id)');
    }
}
