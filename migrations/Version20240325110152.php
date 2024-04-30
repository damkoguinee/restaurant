<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240325110152 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cocktail_recette ADD boisson_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE cocktail_recette ADD CONSTRAINT FK_8A60C8AC734B8089 FOREIGN KEY (boisson_id) REFERENCES boisson (id)');
        $this->addSql('CREATE INDEX IDX_8A60C8AC734B8089 ON cocktail_recette (boisson_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cocktail_recette DROP FOREIGN KEY FK_8A60C8AC734B8089');
        $this->addSql('DROP INDEX IDX_8A60C8AC734B8089 ON cocktail_recette');
        $this->addSql('ALTER TABLE cocktail_recette DROP boisson_id');
    }
}
