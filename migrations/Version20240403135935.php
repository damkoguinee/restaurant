<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240403135935 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE facturation ADD caisse_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE facturation ADD CONSTRAINT FK_17EB513A27B4FEBF FOREIGN KEY (caisse_id) REFERENCES caisse (id)');
        $this->addSql('CREATE INDEX IDX_17EB513A27B4FEBF ON facturation (caisse_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE facturation DROP FOREIGN KEY FK_17EB513A27B4FEBF');
        $this->addSql('DROP INDEX IDX_17EB513A27B4FEBF ON facturation');
        $this->addSql('ALTER TABLE facturation DROP caisse_id');
    }
}
