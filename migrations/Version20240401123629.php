<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240401123629 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE achat_fournisseur (id INT AUTO_INCREMENT NOT NULL, fournisseur_id INT NOT NULL, lieu_vente_id INT NOT NULL, devise_id INT NOT NULL, personnel_id INT NOT NULL, taux DOUBLE PRECISION NOT NULL, numero_facture VARCHAR(100) NOT NULL, type_product VARCHAR(255) DEFAULT NULL, commentaire VARCHAR(255) NOT NULL, montant NUMERIC(13, 2) DEFAULT NULL, date_facture DATE NOT NULL, date_saisie DATETIME NOT NULL, document VARCHAR(255) DEFAULT NULL, etat_paiement VARCHAR(15) NOT NULL, tva DOUBLE PRECISION DEFAULT NULL, INDEX IDX_D8FB1B0F670C757F (fournisseur_id), INDEX IDX_D8FB1B0FAA2B41DC (lieu_vente_id), INDEX IDX_D8FB1B0FF4445056 (devise_id), INDEX IDX_D8FB1B0F1C109075 (personnel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE achat_fournisseur ADD CONSTRAINT FK_D8FB1B0F670C757F FOREIGN KEY (fournisseur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE achat_fournisseur ADD CONSTRAINT FK_D8FB1B0FAA2B41DC FOREIGN KEY (lieu_vente_id) REFERENCES lieux_ventes (id)');
        $this->addSql('ALTER TABLE achat_fournisseur ADD CONSTRAINT FK_D8FB1B0FF4445056 FOREIGN KEY (devise_id) REFERENCES devise (id)');
        $this->addSql('ALTER TABLE achat_fournisseur ADD CONSTRAINT FK_D8FB1B0F1C109075 FOREIGN KEY (personnel_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE achat_fournisseur DROP FOREIGN KEY FK_D8FB1B0F670C757F');
        $this->addSql('ALTER TABLE achat_fournisseur DROP FOREIGN KEY FK_D8FB1B0FAA2B41DC');
        $this->addSql('ALTER TABLE achat_fournisseur DROP FOREIGN KEY FK_D8FB1B0FF4445056');
        $this->addSql('ALTER TABLE achat_fournisseur DROP FOREIGN KEY FK_D8FB1B0F1C109075');
        $this->addSql('DROP TABLE achat_fournisseur');
    }
}
