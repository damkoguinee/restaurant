<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240407075833 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ajustement_solde (id INT AUTO_INCREMENT NOT NULL, collaborateur_id INT NOT NULL, devise_id INT NOT NULL, lieu_vente_id INT NOT NULL, traite_par_id INT NOT NULL, commentaire VARCHAR(255) DEFAULT NULL, montant NUMERIC(13, 2) NOT NULL, date_operation DATETIME NOT NULL, date_saisie DATETIME NOT NULL, INDEX IDX_835EE1E7A848E3B1 (collaborateur_id), INDEX IDX_835EE1E7F4445056 (devise_id), INDEX IDX_835EE1E7AA2B41DC (lieu_vente_id), INDEX IDX_835EE1E7167FABE8 (traite_par_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE versement (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, lieu_vente_id INT NOT NULL, traite_par_id INT NOT NULL, devise_id INT NOT NULL, mode_paie_id INT NOT NULL, compte_id INT NOT NULL, categorie_id INT NOT NULL, reference VARCHAR(255) NOT NULL, montant NUMERIC(13, 2) NOT NULL, taux DOUBLE PRECISION NOT NULL, numero_paiement VARCHAR(100) DEFAULT NULL, banque_cheque VARCHAR(100) DEFAULT NULL, commentaire VARCHAR(255) DEFAULT NULL, date_versement DATETIME NOT NULL, date_saisie DATETIME NOT NULL, INDEX IDX_716E936719EB6921 (client_id), INDEX IDX_716E9367AA2B41DC (lieu_vente_id), INDEX IDX_716E9367167FABE8 (traite_par_id), INDEX IDX_716E9367F4445056 (devise_id), INDEX IDX_716E936762E04A07 (mode_paie_id), INDEX IDX_716E9367F2C56620 (compte_id), INDEX IDX_716E9367BCF5E72D (categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ajustement_solde ADD CONSTRAINT FK_835EE1E7A848E3B1 FOREIGN KEY (collaborateur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE ajustement_solde ADD CONSTRAINT FK_835EE1E7F4445056 FOREIGN KEY (devise_id) REFERENCES devise (id)');
        $this->addSql('ALTER TABLE ajustement_solde ADD CONSTRAINT FK_835EE1E7AA2B41DC FOREIGN KEY (lieu_vente_id) REFERENCES lieux_ventes (id)');
        $this->addSql('ALTER TABLE ajustement_solde ADD CONSTRAINT FK_835EE1E7167FABE8 FOREIGN KEY (traite_par_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE versement ADD CONSTRAINT FK_716E936719EB6921 FOREIGN KEY (client_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE versement ADD CONSTRAINT FK_716E9367AA2B41DC FOREIGN KEY (lieu_vente_id) REFERENCES lieux_ventes (id)');
        $this->addSql('ALTER TABLE versement ADD CONSTRAINT FK_716E9367167FABE8 FOREIGN KEY (traite_par_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE versement ADD CONSTRAINT FK_716E9367F4445056 FOREIGN KEY (devise_id) REFERENCES devise (id)');
        $this->addSql('ALTER TABLE versement ADD CONSTRAINT FK_716E936762E04A07 FOREIGN KEY (mode_paie_id) REFERENCES mode_paiement (id)');
        $this->addSql('ALTER TABLE versement ADD CONSTRAINT FK_716E9367F2C56620 FOREIGN KEY (compte_id) REFERENCES caisse (id)');
        $this->addSql('ALTER TABLE versement ADD CONSTRAINT FK_716E9367BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie_decaissement (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ajustement_solde DROP FOREIGN KEY FK_835EE1E7A848E3B1');
        $this->addSql('ALTER TABLE ajustement_solde DROP FOREIGN KEY FK_835EE1E7F4445056');
        $this->addSql('ALTER TABLE ajustement_solde DROP FOREIGN KEY FK_835EE1E7AA2B41DC');
        $this->addSql('ALTER TABLE ajustement_solde DROP FOREIGN KEY FK_835EE1E7167FABE8');
        $this->addSql('ALTER TABLE versement DROP FOREIGN KEY FK_716E936719EB6921');
        $this->addSql('ALTER TABLE versement DROP FOREIGN KEY FK_716E9367AA2B41DC');
        $this->addSql('ALTER TABLE versement DROP FOREIGN KEY FK_716E9367167FABE8');
        $this->addSql('ALTER TABLE versement DROP FOREIGN KEY FK_716E9367F4445056');
        $this->addSql('ALTER TABLE versement DROP FOREIGN KEY FK_716E936762E04A07');
        $this->addSql('ALTER TABLE versement DROP FOREIGN KEY FK_716E9367F2C56620');
        $this->addSql('ALTER TABLE versement DROP FOREIGN KEY FK_716E9367BCF5E72D');
        $this->addSql('DROP TABLE ajustement_solde');
        $this->addSql('DROP TABLE versement');
    }
}
