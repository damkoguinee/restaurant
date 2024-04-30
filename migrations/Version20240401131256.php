<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240401131256 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE liste_product_achat_fournisseur (id INT AUTO_INCREMENT NOT NULL, achat_fournisseur_id INT NOT NULL, product_id INT NOT NULL, stock_id INT NOT NULL, personnel_id INT NOT NULL, quantite DOUBLE PRECISION NOT NULL, prix_achat NUMERIC(13, 2) DEFAULT NULL, prix_revient NUMERIC(13, 2) DEFAULT NULL, taux DOUBLE PRECISION DEFAULT NULL, commentaire VARCHAR(255) DEFAULT NULL, date_saisie DATETIME NOT NULL, INDEX IDX_62BFDB83EE4462DB (achat_fournisseur_id), INDEX IDX_62BFDB834584665A (product_id), INDEX IDX_62BFDB83DCD6110 (stock_id), INDEX IDX_62BFDB831C109075 (personnel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mouvement_collaborateur (id INT AUTO_INCREMENT NOT NULL, collaborateur_id INT NOT NULL, devise_id INT NOT NULL, caisse_id INT NOT NULL, lieu_vente_id INT NOT NULL, traite_par_id INT NOT NULL, achat_fournisseur_id INT DEFAULT NULL, decaissement_id INT DEFAULT NULL, facture_id INT DEFAULT NULL, origine VARCHAR(100) NOT NULL, montant NUMERIC(13, 2) DEFAULT NULL, date_operation DATETIME NOT NULL, date_saisie DATETIME NOT NULL, INDEX IDX_C47441A2A848E3B1 (collaborateur_id), INDEX IDX_C47441A2F4445056 (devise_id), INDEX IDX_C47441A227B4FEBF (caisse_id), INDEX IDX_C47441A2AA2B41DC (lieu_vente_id), INDEX IDX_C47441A2167FABE8 (traite_par_id), INDEX IDX_C47441A2EE4462DB (achat_fournisseur_id), INDEX IDX_C47441A299E4C234 (decaissement_id), INDEX IDX_C47441A27F2DEE08 (facture_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mouvement_product (id INT AUTO_INCREMENT NOT NULL, stock_product_id INT NOT NULL, product_id INT NOT NULL, lieu_vente_id INT NOT NULL, personnel_id INT NOT NULL, client_id INT DEFAULT NULL, achat_fournisseur_id INT DEFAULT NULL, origine VARCHAR(100) NOT NULL, description VARCHAR(150) DEFAULT NULL, quantite DOUBLE PRECISION DEFAULT NULL, date_operation DATETIME NOT NULL, INDEX IDX_1C8AD1BAEBCD91F6 (stock_product_id), INDEX IDX_1C8AD1BA4584665A (product_id), INDEX IDX_1C8AD1BAAA2B41DC (lieu_vente_id), INDEX IDX_1C8AD1BA1C109075 (personnel_id), INDEX IDX_1C8AD1BA19EB6921 (client_id), INDEX IDX_1C8AD1BAEE4462DB (achat_fournisseur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE liste_product_achat_fournisseur ADD CONSTRAINT FK_62BFDB83EE4462DB FOREIGN KEY (achat_fournisseur_id) REFERENCES achat_fournisseur (id)');
        $this->addSql('ALTER TABLE liste_product_achat_fournisseur ADD CONSTRAINT FK_62BFDB834584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE liste_product_achat_fournisseur ADD CONSTRAINT FK_62BFDB83DCD6110 FOREIGN KEY (stock_id) REFERENCES liste_stock (id)');
        $this->addSql('ALTER TABLE liste_product_achat_fournisseur ADD CONSTRAINT FK_62BFDB831C109075 FOREIGN KEY (personnel_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE mouvement_collaborateur ADD CONSTRAINT FK_C47441A2A848E3B1 FOREIGN KEY (collaborateur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE mouvement_collaborateur ADD CONSTRAINT FK_C47441A2F4445056 FOREIGN KEY (devise_id) REFERENCES devise (id)');
        $this->addSql('ALTER TABLE mouvement_collaborateur ADD CONSTRAINT FK_C47441A227B4FEBF FOREIGN KEY (caisse_id) REFERENCES caisse (id)');
        $this->addSql('ALTER TABLE mouvement_collaborateur ADD CONSTRAINT FK_C47441A2AA2B41DC FOREIGN KEY (lieu_vente_id) REFERENCES lieux_ventes (id)');
        $this->addSql('ALTER TABLE mouvement_collaborateur ADD CONSTRAINT FK_C47441A2167FABE8 FOREIGN KEY (traite_par_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE mouvement_collaborateur ADD CONSTRAINT FK_C47441A2EE4462DB FOREIGN KEY (achat_fournisseur_id) REFERENCES achat_fournisseur (id)');
        $this->addSql('ALTER TABLE mouvement_collaborateur ADD CONSTRAINT FK_C47441A299E4C234 FOREIGN KEY (decaissement_id) REFERENCES decaissement (id)');
        $this->addSql('ALTER TABLE mouvement_collaborateur ADD CONSTRAINT FK_C47441A27F2DEE08 FOREIGN KEY (facture_id) REFERENCES facturation (id)');
        $this->addSql('ALTER TABLE mouvement_product ADD CONSTRAINT FK_1C8AD1BAEBCD91F6 FOREIGN KEY (stock_product_id) REFERENCES liste_stock (id)');
        $this->addSql('ALTER TABLE mouvement_product ADD CONSTRAINT FK_1C8AD1BA4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE mouvement_product ADD CONSTRAINT FK_1C8AD1BAAA2B41DC FOREIGN KEY (lieu_vente_id) REFERENCES lieux_ventes (id)');
        $this->addSql('ALTER TABLE mouvement_product ADD CONSTRAINT FK_1C8AD1BA1C109075 FOREIGN KEY (personnel_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE mouvement_product ADD CONSTRAINT FK_1C8AD1BA19EB6921 FOREIGN KEY (client_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE mouvement_product ADD CONSTRAINT FK_1C8AD1BAEE4462DB FOREIGN KEY (achat_fournisseur_id) REFERENCES achat_fournisseur (id)');
        $this->addSql('ALTER TABLE mouvement_caisse ADD lieu_vente_id INT NOT NULL, ADD caisse_id INT NOT NULL, ADD categorie_operation_id INT NOT NULL, ADD compte_operation_id INT NOT NULL, ADD devise_id INT NOT NULL, ADD decaissement_id INT DEFAULT NULL, ADD depense_id INT DEFAULT NULL, ADD facturation_id INT DEFAULT NULL, ADD mode_paie_id INT DEFAULT NULL, ADD type_mouvement VARCHAR(255) NOT NULL, ADD montant NUMERIC(13, 2) NOT NULL, ADD date_operation DATETIME NOT NULL, ADD date_saisie DATETIME NOT NULL, ADD numero_paie VARCHAR(100) DEFAULT NULL, ADD banque_cheque VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE mouvement_caisse ADD CONSTRAINT FK_C8E3DDFEAA2B41DC FOREIGN KEY (lieu_vente_id) REFERENCES lieux_ventes (id)');
        $this->addSql('ALTER TABLE mouvement_caisse ADD CONSTRAINT FK_C8E3DDFE27B4FEBF FOREIGN KEY (caisse_id) REFERENCES caisse (id)');
        $this->addSql('ALTER TABLE mouvement_caisse ADD CONSTRAINT FK_C8E3DDFE3DAF7301 FOREIGN KEY (categorie_operation_id) REFERENCES categorie_operation (id)');
        $this->addSql('ALTER TABLE mouvement_caisse ADD CONSTRAINT FK_C8E3DDFE2886B1E4 FOREIGN KEY (compte_operation_id) REFERENCES compte_operation (id)');
        $this->addSql('ALTER TABLE mouvement_caisse ADD CONSTRAINT FK_C8E3DDFEF4445056 FOREIGN KEY (devise_id) REFERENCES devise (id)');
        $this->addSql('ALTER TABLE mouvement_caisse ADD CONSTRAINT FK_C8E3DDFE99E4C234 FOREIGN KEY (decaissement_id) REFERENCES decaissement (id)');
        $this->addSql('ALTER TABLE mouvement_caisse ADD CONSTRAINT FK_C8E3DDFE41D81563 FOREIGN KEY (depense_id) REFERENCES depense (id)');
        $this->addSql('ALTER TABLE mouvement_caisse ADD CONSTRAINT FK_C8E3DDFEDBC5F284 FOREIGN KEY (facturation_id) REFERENCES facturation (id)');
        $this->addSql('ALTER TABLE mouvement_caisse ADD CONSTRAINT FK_C8E3DDFE62E04A07 FOREIGN KEY (mode_paie_id) REFERENCES mode_paiement (id)');
        $this->addSql('CREATE INDEX IDX_C8E3DDFEAA2B41DC ON mouvement_caisse (lieu_vente_id)');
        $this->addSql('CREATE INDEX IDX_C8E3DDFE27B4FEBF ON mouvement_caisse (caisse_id)');
        $this->addSql('CREATE INDEX IDX_C8E3DDFE3DAF7301 ON mouvement_caisse (categorie_operation_id)');
        $this->addSql('CREATE INDEX IDX_C8E3DDFE2886B1E4 ON mouvement_caisse (compte_operation_id)');
        $this->addSql('CREATE INDEX IDX_C8E3DDFEF4445056 ON mouvement_caisse (devise_id)');
        $this->addSql('CREATE INDEX IDX_C8E3DDFE99E4C234 ON mouvement_caisse (decaissement_id)');
        $this->addSql('CREATE INDEX IDX_C8E3DDFE41D81563 ON mouvement_caisse (depense_id)');
        $this->addSql('CREATE INDEX IDX_C8E3DDFEDBC5F284 ON mouvement_caisse (facturation_id)');
        $this->addSql('CREATE INDEX IDX_C8E3DDFE62E04A07 ON mouvement_caisse (mode_paie_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE liste_product_achat_fournisseur DROP FOREIGN KEY FK_62BFDB83EE4462DB');
        $this->addSql('ALTER TABLE liste_product_achat_fournisseur DROP FOREIGN KEY FK_62BFDB834584665A');
        $this->addSql('ALTER TABLE liste_product_achat_fournisseur DROP FOREIGN KEY FK_62BFDB83DCD6110');
        $this->addSql('ALTER TABLE liste_product_achat_fournisseur DROP FOREIGN KEY FK_62BFDB831C109075');
        $this->addSql('ALTER TABLE mouvement_collaborateur DROP FOREIGN KEY FK_C47441A2A848E3B1');
        $this->addSql('ALTER TABLE mouvement_collaborateur DROP FOREIGN KEY FK_C47441A2F4445056');
        $this->addSql('ALTER TABLE mouvement_collaborateur DROP FOREIGN KEY FK_C47441A227B4FEBF');
        $this->addSql('ALTER TABLE mouvement_collaborateur DROP FOREIGN KEY FK_C47441A2AA2B41DC');
        $this->addSql('ALTER TABLE mouvement_collaborateur DROP FOREIGN KEY FK_C47441A2167FABE8');
        $this->addSql('ALTER TABLE mouvement_collaborateur DROP FOREIGN KEY FK_C47441A2EE4462DB');
        $this->addSql('ALTER TABLE mouvement_collaborateur DROP FOREIGN KEY FK_C47441A299E4C234');
        $this->addSql('ALTER TABLE mouvement_collaborateur DROP FOREIGN KEY FK_C47441A27F2DEE08');
        $this->addSql('ALTER TABLE mouvement_product DROP FOREIGN KEY FK_1C8AD1BAEBCD91F6');
        $this->addSql('ALTER TABLE mouvement_product DROP FOREIGN KEY FK_1C8AD1BA4584665A');
        $this->addSql('ALTER TABLE mouvement_product DROP FOREIGN KEY FK_1C8AD1BAAA2B41DC');
        $this->addSql('ALTER TABLE mouvement_product DROP FOREIGN KEY FK_1C8AD1BA1C109075');
        $this->addSql('ALTER TABLE mouvement_product DROP FOREIGN KEY FK_1C8AD1BA19EB6921');
        $this->addSql('ALTER TABLE mouvement_product DROP FOREIGN KEY FK_1C8AD1BAEE4462DB');
        $this->addSql('DROP TABLE liste_product_achat_fournisseur');
        $this->addSql('DROP TABLE mouvement_collaborateur');
        $this->addSql('DROP TABLE mouvement_product');
        $this->addSql('ALTER TABLE mouvement_caisse DROP FOREIGN KEY FK_C8E3DDFEAA2B41DC');
        $this->addSql('ALTER TABLE mouvement_caisse DROP FOREIGN KEY FK_C8E3DDFE27B4FEBF');
        $this->addSql('ALTER TABLE mouvement_caisse DROP FOREIGN KEY FK_C8E3DDFE3DAF7301');
        $this->addSql('ALTER TABLE mouvement_caisse DROP FOREIGN KEY FK_C8E3DDFE2886B1E4');
        $this->addSql('ALTER TABLE mouvement_caisse DROP FOREIGN KEY FK_C8E3DDFEF4445056');
        $this->addSql('ALTER TABLE mouvement_caisse DROP FOREIGN KEY FK_C8E3DDFE99E4C234');
        $this->addSql('ALTER TABLE mouvement_caisse DROP FOREIGN KEY FK_C8E3DDFE41D81563');
        $this->addSql('ALTER TABLE mouvement_caisse DROP FOREIGN KEY FK_C8E3DDFEDBC5F284');
        $this->addSql('ALTER TABLE mouvement_caisse DROP FOREIGN KEY FK_C8E3DDFE62E04A07');
        $this->addSql('DROP INDEX IDX_C8E3DDFEAA2B41DC ON mouvement_caisse');
        $this->addSql('DROP INDEX IDX_C8E3DDFE27B4FEBF ON mouvement_caisse');
        $this->addSql('DROP INDEX IDX_C8E3DDFE3DAF7301 ON mouvement_caisse');
        $this->addSql('DROP INDEX IDX_C8E3DDFE2886B1E4 ON mouvement_caisse');
        $this->addSql('DROP INDEX IDX_C8E3DDFEF4445056 ON mouvement_caisse');
        $this->addSql('DROP INDEX IDX_C8E3DDFE99E4C234 ON mouvement_caisse');
        $this->addSql('DROP INDEX IDX_C8E3DDFE41D81563 ON mouvement_caisse');
        $this->addSql('DROP INDEX IDX_C8E3DDFEDBC5F284 ON mouvement_caisse');
        $this->addSql('DROP INDEX IDX_C8E3DDFE62E04A07 ON mouvement_caisse');
        $this->addSql('ALTER TABLE mouvement_caisse DROP lieu_vente_id, DROP caisse_id, DROP categorie_operation_id, DROP compte_operation_id, DROP devise_id, DROP decaissement_id, DROP depense_id, DROP facturation_id, DROP mode_paie_id, DROP type_mouvement, DROP montant, DROP date_operation, DROP date_saisie, DROP numero_paie, DROP banque_cheque');
    }
}
