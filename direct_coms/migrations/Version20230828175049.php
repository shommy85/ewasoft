<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230828175049 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE INDEX messages_sender_id_idx ON message (sender_id)');
        $this->addSql('CREATE INDEX messages_recipient_id_idx ON message (recipient_id)');
        $this->addSql('CREATE INDEX messages_timestamp_idx ON message (timestamp)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX messages_sender_id_idx');
        $this->addSql('DROP INDEX messages_recipient_id_idx');
        $this->addSql('DROP INDEX messages_timestamp_idx');
    }
}
