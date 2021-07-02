<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210323202611 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message ADD user_recipient_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F69E3F37A FOREIGN KEY (user_recipient_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_B6BD307F69E3F37A ON message (user_recipient_id)');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649A5905F5A');
        $this->addSql('DROP INDEX IDX_8D93D649A5905F5A ON user');
        $this->addSql('ALTER TABLE user DROP messages_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F69E3F37A');
        $this->addSql('DROP INDEX IDX_B6BD307F69E3F37A ON message');
        $this->addSql('ALTER TABLE message DROP user_recipient_id');
        $this->addSql('ALTER TABLE user ADD messages_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649A5905F5A FOREIGN KEY (messages_id) REFERENCES message (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649A5905F5A ON user (messages_id)');
    }
}
