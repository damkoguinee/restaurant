<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240407091400 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE modification_facture (id INT AUTO_INCREMENT NOT NULL, facture_id INT NOT NULL, client_id INT DEFAULT NULL, mode_paie_id INT DEFAULT NULL, saisie_par_id INT NOT NULL, lieu_vente_id INT NOT NULL, caisse_id INT DEFAULT NULL, numero_facture VARCHAR(50) DEFAULT NULL, montant_total NUMERIC(13, 2) DEFAULT NULL, montant_paye NUMERIC(13, 2) DEFAULT NULL, frais_sup NUMERIC(13, 2) DEFAULT NULL, remise NUMERIC(13, 2) DEFAULT NULL, date_facturation DATETIME NOT NULL, date_saisie DATETIME NOT NULL, INDEX IDX_6A92FF757F2DEE08 (facture_id), INDEX IDX_6A92FF7519EB6921 (client_id), INDEX IDX_6A92FF7562E04A07 (mode_paie_id), INDEX IDX_6A92FF75C74AC7FE (saisie_par_id), INDEX IDX_6A92FF75AA2B41DC (lieu_vente_id), INDEX IDX_6A92FF7527B4FEBF (caisse_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE suppression_facture (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, mode_paie_id INT DEFAULT NULL, saisie_par_id INT NOT NULL, lieu_vente_id INT NOT NULL, caisse_id INT DEFAULT NULL, numero_facture VARCHAR(50) NOT NULL, montant_total NUMERIC(13, 2) DEFAULT NULL, montant_paye NUMERIC(13, 2) DEFAULT NULL, frais_sup NUMERIC(13, 2) DEFAULT NULL, remise NUMERIC(13, 2) NOT NULL, table_commande VARCHAR(50) DEFAULT NULL, type_vente VARCHAR(50) DEFAULT NULL, date_facturation DATETIME NOT NULL, date_saisie DATETIME NOT NULL, INDEX IDX_15A6587019EB6921 (client_id), INDEX IDX_15A6587062E04A07 (mode_paie_id), INDEX IDX_15A65870C74AC7FE (saisie_par_id), INDEX IDX_15A65870AA2B41DC (lieu_vente_id), INDEX IDX_15A6587027B4FEBF (caisse_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE modification_facture ADD CONSTRAINT FK_6A92FF757F2DEE08 FOREIGN KEY (facture_id) REFERENCES facturation (id)');
        $this->addSql('ALTER TABLE modification_facture ADD CONSTRAINT FK_6A92FF7519EB6921 FOREIGN KEY (client_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE modification_facture ADD CONSTRAINT FK_6A92FF7562E04A07 FOREIGN KEY (mode_paie_id) REFERENCES mode_paiement (id)');
        $this->addSql('ALTER TABLE modification_facture ADD CONSTRAINT FK_6A92FF75C74AC7FE FOREIGN KEY (saisie_par_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE modification_facture ADD CONSTRAINT FK_6A92FF75AA2B41DC FOREIGN KEY (lieu_vente_id) REFERENCES lieux_ventes (id)');
        $this->addSql('ALTER TABLE modification_facture ADD CONSTRAINT FK_6A92FF7527B4FEBF FOREIGN KEY (caisse_id) REFERENCES caisse (id)');
        $this->addSql('ALTER TABLE suppression_facture ADD CONSTRAINT FK_15A6587019EB6921 FOREIGN KEY (client_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE suppression_facture ADD CONSTRAINT FK_15A6587062E04A07 FOREIGN KEY (mode_paie_id) REFERENCES mode_paiement (id)');
        $this->addSql('ALTER TABLE suppression_facture ADD CONSTRAINT FK_15A65870C74AC7FE FOREIGN KEY (saisie_par_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE suppression_facture ADD CONSTRAINT FK_15A65870AA2B41DC FOREIGN KEY (lieu_vente_id) REFERENCES lieux_ventes (id)');
        $this->addSql('ALTER TABLE suppression_facture ADD CONSTRAINT FK_15A6587027B4FEBF FOREIGN KEY (caisse_id) REFERENCES caisse (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE modification_facture DROP FOREIGN KEY FK_6A92FF757F2DEE08');
        $this->addSql('ALTER TABLE modification_facture DROP FOREIGN KEY FK_6A92FF7519EB6921');
        $this->addSql('ALTER TABLE modification_facture DROP FOREIGN KEY FK_6A92FF7562E04A07');
        $this->addSql('ALTER TABLE modification_facture DROP FOREIGN KEY FK_6A92FF75C74AC7FE');
        $this->addSql('ALTER TABLE modification_facture DROP FOREIGN KEY FK_6A92FF75AA2B41DC');
        $this->addSql('ALTER TABLE modification_facture DROP FOREIGN KEY FK_6A92FF7527B4FEBF');
        $this->addSql('ALTER TABLE suppression_facture DROP FOREIGN KEY FK_15A6587019EB6921');
        $this->addSql('ALTER TABLE suppression_facture DROP FOREIGN KEY FK_15A6587062E04A07');
        $this->addSql('ALTER TABLE suppression_facture DROP FOREIGN KEY FK_15A65870C74AC7FE');
        $this->addSql('ALTER TABLE suppression_facture DROP FOREIGN KEY FK_15A65870AA2B41DC');
        $this->addSql('ALTER TABLE suppression_facture DROP FOREIGN KEY FK_15A6587027B4FEBF');
        $this->addSql('DROP TABLE modification_facture');
        $this->addSql('DROP TABLE suppression_facture');
    }
}
