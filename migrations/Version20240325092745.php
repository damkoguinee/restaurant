<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240325092745 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorie_boisson (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, alcoolise TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE boisson ADD categorie_id INT NOT NULL, ADD type VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE boisson ADD CONSTRAINT FK_8B97C84DBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie_boisson (id)');
        $this->addSql('CREATE INDEX IDX_8B97C84DBCF5E72D ON boisson (categorie_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE boisson DROP FOREIGN KEY FK_8B97C84DBCF5E72D');
        $this->addSql('DROP TABLE categorie_boisson');
        $this->addSql('DROP INDEX IDX_8B97C84DBCF5E72D ON boisson');
        $this->addSql('ALTER TABLE boisson DROP categorie_id, DROP type');
    }
}
