<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210312204833 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cv (id INT AUTO_INCREMENT NOT NULL, cv_name VARCHAR(150) DEFAULT NULL, cv_size INT DEFAULT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user ADD cv_id INT DEFAULT NULL, DROP cv');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649CFE419E2 FOREIGN KEY (cv_id) REFERENCES cv (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649CFE419E2 ON user (cv_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649CFE419E2');
        $this->addSql('DROP TABLE cv');
        $this->addSql('DROP INDEX IDX_8D93D649CFE419E2 ON user');
        $this->addSql('ALTER TABLE user ADD cv VARCHAR(150) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, DROP cv_id');
    }
}
