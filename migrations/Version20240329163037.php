<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240329163037 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE boisson ADD categorie_id INT NOT NULL, ADD type_boisson VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE boisson ADD CONSTRAINT FK_8B97C84DBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie_boisson (id)');
        $this->addSql('CREATE INDEX IDX_8B97C84DBCF5E72D ON boisson (categorie_id)');
        $this->addSql('ALTER TABLE plat ADD type_plat_id INT NOT NULL');
        $this->addSql('ALTER TABLE plat ADD CONSTRAINT FK_2038A207FF1CDA95 FOREIGN KEY (type_plat_id) REFERENCES type_plat (id)');
        $this->addSql('CREATE INDEX IDX_2038A207FF1CDA95 ON plat (type_plat_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE boisson DROP FOREIGN KEY FK_8B97C84DBCF5E72D');
        $this->addSql('DROP INDEX IDX_8B97C84DBCF5E72D ON boisson');
        $this->addSql('ALTER TABLE boisson DROP categorie_id, DROP type_boisson');
        $this->addSql('ALTER TABLE plat DROP FOREIGN KEY FK_2038A207FF1CDA95');
        $this->addSql('DROP INDEX IDX_2038A207FF1CDA95 ON plat');
        $this->addSql('ALTER TABLE plat DROP type_plat_id');
    }
}
