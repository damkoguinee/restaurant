<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240326140331 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE menu_vente (id INT AUTO_INCREMENT NOT NULL, lieu_vente_id INT NOT NULL, nom VARCHAR(150) NOT NULL, ordre INT NOT NULL, INDEX IDX_1926092BAA2B41DC (lieu_vente_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE menu_vente ADD CONSTRAINT FK_1926092BAA2B41DC FOREIGN KEY (lieu_vente_id) REFERENCES lieux_ventes (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE menu_vente DROP FOREIGN KEY FK_1926092BAA2B41DC');
        $this->addSql('DROP TABLE menu_vente');
    }
}
