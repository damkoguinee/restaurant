<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240326105100 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE facturation (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, mode_payment_id INT DEFAULT NULL, saisie_par_id INT NOT NULL, lieu_vente_id INT NOT NULL, numero_facture VARCHAR(50) NOT NULL, numero_ticket VARCHAR(50) NOT NULL, montant_total NUMERIC(13, 2) DEFAULT NULL, montant_paye NUMERIC(13, 2) DEFAULT NULL, frais_sup NUMERIC(13, 2) NOT NULL, remise NUMERIC(13, 2) DEFAULT NULL, table_commande VARCHAR(50) DEFAULT NULL, etat VARCHAR(50) NOT NULL, type_vente VARCHAR(50) NOT NULL, commentaire VARCHAR(255) DEFAULT NULL, date_facturation DATETIME NOT NULL, date_saisie DATETIME NOT NULL, livraison VARCHAR(15) NOT NULL, INDEX IDX_17EB513A19EB6921 (client_id), INDEX IDX_17EB513AC11E4628 (mode_payment_id), INDEX IDX_17EB513AC74AC7FE (saisie_par_id), INDEX IDX_17EB513AAA2B41DC (lieu_vente_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE salle (id INT AUTO_INCREMENT NOT NULL, lieu_vente_id INT NOT NULL, nom VARCHAR(100) NOT NULL, capacite INT DEFAULT NULL, INDEX IDX_4E977E5CAA2B41DC (lieu_vente_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE facturation ADD CONSTRAINT FK_17EB513A19EB6921 FOREIGN KEY (client_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE facturation ADD CONSTRAINT FK_17EB513AC11E4628 FOREIGN KEY (mode_payment_id) REFERENCES mode_paiement (id)');
        $this->addSql('ALTER TABLE facturation ADD CONSTRAINT FK_17EB513AC74AC7FE FOREIGN KEY (saisie_par_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE facturation ADD CONSTRAINT FK_17EB513AAA2B41DC FOREIGN KEY (lieu_vente_id) REFERENCES lieux_ventes (id)');
        $this->addSql('ALTER TABLE salle ADD CONSTRAINT FK_4E977E5CAA2B41DC FOREIGN KEY (lieu_vente_id) REFERENCES lieux_ventes (id)');
        $this->addSql('ALTER TABLE `table` DROP FOREIGN KEY FK_F6298F46C4598A51');
        $this->addSql('DROP INDEX IDX_F6298F46C4598A51 ON `table`');
        $this->addSql('ALTER TABLE `table` ADD lieu_vente_id INT NOT NULL, CHANGE emplacement_id salle_id INT NOT NULL');
        $this->addSql('ALTER TABLE `table` ADD CONSTRAINT FK_F6298F46DC304035 FOREIGN KEY (salle_id) REFERENCES salle (id)');
        $this->addSql('ALTER TABLE `table` ADD CONSTRAINT FK_F6298F46AA2B41DC FOREIGN KEY (lieu_vente_id) REFERENCES lieux_ventes (id)');
        $this->addSql('CREATE INDEX IDX_F6298F46DC304035 ON `table` (salle_id)');
        $this->addSql('CREATE INDEX IDX_F6298F46AA2B41DC ON `table` (lieu_vente_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `table` DROP FOREIGN KEY FK_F6298F46DC304035');
        $this->addSql('ALTER TABLE facturation DROP FOREIGN KEY FK_17EB513A19EB6921');
        $this->addSql('ALTER TABLE facturation DROP FOREIGN KEY FK_17EB513AC11E4628');
        $this->addSql('ALTER TABLE facturation DROP FOREIGN KEY FK_17EB513AC74AC7FE');
        $this->addSql('ALTER TABLE facturation DROP FOREIGN KEY FK_17EB513AAA2B41DC');
        $this->addSql('ALTER TABLE salle DROP FOREIGN KEY FK_4E977E5CAA2B41DC');
        $this->addSql('DROP TABLE facturation');
        $this->addSql('DROP TABLE salle');
        $this->addSql('ALTER TABLE `table` DROP FOREIGN KEY FK_F6298F46AA2B41DC');
        $this->addSql('DROP INDEX IDX_F6298F46DC304035 ON `table`');
        $this->addSql('DROP INDEX IDX_F6298F46AA2B41DC ON `table`');
        $this->addSql('ALTER TABLE `table` ADD emplacement_id INT NOT NULL, DROP salle_id, DROP lieu_vente_id');
        $this->addSql('ALTER TABLE `table` ADD CONSTRAINT FK_F6298F46C4598A51 FOREIGN KEY (emplacement_id) REFERENCES lieux_ventes (id)');
        $this->addSql('CREATE INDEX IDX_F6298F46C4598A51 ON `table` (emplacement_id)');
    }
}
