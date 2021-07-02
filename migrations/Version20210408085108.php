<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210408085108 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cv ADD favorite_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE cv ADD CONSTRAINT FK_B66FFE92AA17481D FOREIGN KEY (favorite_id) REFERENCES favorite (id)');
        $this->addSql('CREATE INDEX IDX_B66FFE92AA17481D ON cv (favorite_id)');
        $this->addSql('ALTER TABLE job_offer ADD favorite_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE job_offer ADD CONSTRAINT FK_288A3A4EAA17481D FOREIGN KEY (favorite_id) REFERENCES favorite (id)');
        $this->addSql('CREATE INDEX IDX_288A3A4EAA17481D ON job_offer (favorite_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cv DROP FOREIGN KEY FK_B66FFE92AA17481D');
        $this->addSql('DROP INDEX IDX_B66FFE92AA17481D ON cv');
        $this->addSql('ALTER TABLE cv DROP favorite_id');
        $this->addSql('ALTER TABLE job_offer DROP FOREIGN KEY FK_288A3A4EAA17481D');
        $this->addSql('DROP INDEX IDX_288A3A4EAA17481D ON job_offer');
        $this->addSql('ALTER TABLE job_offer DROP favorite_id');
    }
}
