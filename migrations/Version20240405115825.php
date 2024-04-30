<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240405115825 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE modification_commande (id INT AUTO_INCREMENT NOT NULL, saisie_par_id INT NOT NULL, modifier_par_id INT NOT NULL, quantite DOUBLE PRECISION NOT NULL, prix_vente NUMERIC(13, 2) DEFAULT NULL, date_commande DATETIME NOT NULL, date_saisie DATETIME NOT NULL, type_vente VARCHAR(50) NOT NULL, commentaire VARCHAR(255) DEFAULT NULL, statut VARCHAR(50) NOT NULL, type_product VARCHAR(100) DEFAULT NULL, INDEX IDX_53A62731C74AC7FE (saisie_par_id), INDEX IDX_53A6273187D27616 (modifier_par_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE modification_commande ADD CONSTRAINT FK_53A62731C74AC7FE FOREIGN KEY (saisie_par_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE modification_commande ADD CONSTRAINT FK_53A6273187D27616 FOREIGN KEY (modifier_par_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE modification_commande DROP FOREIGN KEY FK_53A62731C74AC7FE');
        $this->addSql('ALTER TABLE modification_commande DROP FOREIGN KEY FK_53A6273187D27616');
        $this->addSql('DROP TABLE modification_commande');
    }
}
