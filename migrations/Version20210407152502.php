<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210407152502 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE favorite (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, INDEX IDX_68C58ED9A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE candidate_commitment (candidate_id INT NOT NULL, commitment_id INT NOT NULL, INDEX IDX_C127DB8591BD8781 (candidate_id), INDEX IDX_C127DB85680FAE08 (commitment_id), PRIMARY KEY(candidate_id, commitment_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE favorite ADD CONSTRAINT FK_68C58ED9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE candidate_commitment ADD CONSTRAINT FK_C127DB8591BD8781 FOREIGN KEY (candidate_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE candidate_commitment ADD CONSTRAINT FK_C127DB85680FAE08 FOREIGN KEY (commitment_id) REFERENCES commitment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commitment ADD text LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE favorite');
        $this->addSql('DROP TABLE candidate_commitment');
        $this->addSql('ALTER TABLE commitment DROP text');
    }
}
