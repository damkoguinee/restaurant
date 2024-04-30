<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240321114556 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE caisse (id INT AUTO_INCREMENT NOT NULL, point_de_vente_id INT DEFAULT NULL, designation VARCHAR(100) NOT NULL, type VARCHAR(50) NOT NULL, numero_compte VARCHAR(255) DEFAULT NULL, INDEX IDX_B2A353C83F95E273 (point_de_vente_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie_decaissement (id INT AUTO_INCREMENT NOT NULL, designation VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie_depense (id INT AUTO_INCREMENT NOT NULL, description VARCHAR(150) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, rattachement_id INT NOT NULL, type_client VARCHAR(50) NOT NULL, limit_credit NUMERIC(13, 2) DEFAULT NULL, document VARCHAR(255) DEFAULT NULL, societe VARCHAR(255) DEFAULT NULL, INDEX IDX_C7440455A76ED395 (user_id), INDEX IDX_C74404558E780FD (rattachement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE depense (id INT AUTO_INCREMENT NOT NULL, categorie_depense_id INT NOT NULL, description VARCHAR(255) NOT NULL, montant NUMERIC(13, 2) NOT NULL, date_depense DATETIME NOT NULL, date_saisie DATETIME NOT NULL, tva NUMERIC(13, 2) DEFAULT NULL, justificatif VARCHAR(255) DEFAULT NULL, INDEX IDX_34059757DF11DFCF (categorie_depense_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE devise (id INT AUTO_INCREMENT NOT NULL, nom_devise VARCHAR(15) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ecran (id INT AUTO_INCREMENT NOT NULL, service_id INT NOT NULL, etat VARCHAR(50) NOT NULL, code_identification VARCHAR(255) NOT NULL, INDEX IDX_3FFAFD4AED5CA9E6 (service_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE entreprise (id INT AUTO_INCREMENT NOT NULL, nom_entreprise VARCHAR(255) NOT NULL, identifiant VARCHAR(255) NOT NULL, numero_agrement VARCHAR(255) DEFAULT NULL, adresse VARCHAR(255) NOT NULL, telephone VARCHAR(15) NOT NULL, logo VARCHAR(255) DEFAULT NULL, latitude VARCHAR(255) DEFAULT NULL, longitude VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lieux_ventes (id INT AUTO_INCREMENT NOT NULL, enentreprise_id INT NOT NULL, gestionnaire_id INT NOT NULL, lieu VARCHAR(150) NOT NULL, adresse VARCHAR(255) NOT NULL, pays VARCHAR(100) NOT NULL, region VARCHAR(100) NOT NULL, ville VARCHAR(100) NOT NULL, telephone VARCHAR(20) NOT NULL, email VARCHAR(150) DEFAULT NULL, latitude VARCHAR(255) DEFAULT NULL, longitude VARCHAR(255) DEFAULT NULL, initial VARCHAR(10) NOT NULL, type_commerce VARCHAR(255) DEFAULT NULL, INDEX IDX_8FE8FCB74AF6CB4E (enentreprise_id), INDEX IDX_8FE8FCB76885AC1B (gestionnaire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mode_paiement (id INT AUTO_INCREMENT NOT NULL, designation VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE personnel (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, fonction VARCHAR(50) NOT NULL, type_paie VARCHAR(50) NOT NULL, taut_horaire NUMERIC(10, 2) DEFAULT NULL, salaire_base NUMERIC(10, 2) DEFAULT NULL, document_identite VARCHAR(255) DEFAULT NULL, photo_identite VARCHAR(255) DEFAULT NULL, INDEX IDX_A6BCF3DEA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE point_de_vente (id INT AUTO_INCREMENT NOT NULL, lieu_vente_id INT NOT NULL, designation VARCHAR(100) NOT NULL, INDEX IDX_C9182F7BAA2B41DC (lieu_vente_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE region (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(150) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE service (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `table` (id INT AUTO_INCREMENT NOT NULL, emplacement_id INT NOT NULL, nom VARCHAR(100) NOT NULL, capacite INT DEFAULT NULL, etat VARCHAR(50) NOT NULL, INDEX IDX_F6298F46C4598A51 (emplacement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE caisse ADD CONSTRAINT FK_B2A353C83F95E273 FOREIGN KEY (point_de_vente_id) REFERENCES point_de_vente (id)');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C7440455A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C74404558E780FD FOREIGN KEY (rattachement_id) REFERENCES lieux_ventes (id)');
        $this->addSql('ALTER TABLE depense ADD CONSTRAINT FK_34059757DF11DFCF FOREIGN KEY (categorie_depense_id) REFERENCES categorie_depense (id)');
        $this->addSql('ALTER TABLE ecran ADD CONSTRAINT FK_3FFAFD4AED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
        $this->addSql('ALTER TABLE lieux_ventes ADD CONSTRAINT FK_8FE8FCB74AF6CB4E FOREIGN KEY (enentreprise_id) REFERENCES entreprise (id)');
        $this->addSql('ALTER TABLE lieux_ventes ADD CONSTRAINT FK_8FE8FCB76885AC1B FOREIGN KEY (gestionnaire_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE personnel ADD CONSTRAINT FK_A6BCF3DEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE point_de_vente ADD CONSTRAINT FK_C9182F7BAA2B41DC FOREIGN KEY (lieu_vente_id) REFERENCES lieux_ventes (id)');
        $this->addSql('ALTER TABLE `table` ADD CONSTRAINT FK_F6298F46C4598A51 FOREIGN KEY (emplacement_id) REFERENCES lieux_ventes (id)');
        $this->addSql('ALTER TABLE user ADD lieu_vente_id INT NOT NULL, ADD region_id INT DEFAULT NULL, ADD nom VARCHAR(100) NOT NULL, ADD prenom VARCHAR(150) NOT NULL, ADD telephone VARCHAR(20) NOT NULL, ADD email VARCHAR(255) DEFAULT NULL, ADD type_user VARCHAR(50) NOT NULL, ADD adresse VARCHAR(255) NOT NULL, ADD ville VARCHAR(100) NOT NULL, ADD pays VARCHAR(150) NOT NULL, ADD reference VARCHAR(255) NOT NULL, ADD date_creation DATE NOT NULL, ADD statut VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649AA2B41DC FOREIGN KEY (lieu_vente_id) REFERENCES lieux_ventes (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64998260155 FOREIGN KEY (region_id) REFERENCES region (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649AA2B41DC ON user (lieu_vente_id)');
        $this->addSql('CREATE INDEX IDX_8D93D64998260155 ON user (region_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649AA2B41DC');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64998260155');
        $this->addSql('ALTER TABLE caisse DROP FOREIGN KEY FK_B2A353C83F95E273');
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C7440455A76ED395');
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C74404558E780FD');
        $this->addSql('ALTER TABLE depense DROP FOREIGN KEY FK_34059757DF11DFCF');
        $this->addSql('ALTER TABLE ecran DROP FOREIGN KEY FK_3FFAFD4AED5CA9E6');
        $this->addSql('ALTER TABLE lieux_ventes DROP FOREIGN KEY FK_8FE8FCB74AF6CB4E');
        $this->addSql('ALTER TABLE lieux_ventes DROP FOREIGN KEY FK_8FE8FCB76885AC1B');
        $this->addSql('ALTER TABLE personnel DROP FOREIGN KEY FK_A6BCF3DEA76ED395');
        $this->addSql('ALTER TABLE point_de_vente DROP FOREIGN KEY FK_C9182F7BAA2B41DC');
        $this->addSql('ALTER TABLE `table` DROP FOREIGN KEY FK_F6298F46C4598A51');
        $this->addSql('DROP TABLE caisse');
        $this->addSql('DROP TABLE categorie_decaissement');
        $this->addSql('DROP TABLE categorie_depense');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE depense');
        $this->addSql('DROP TABLE devise');
        $this->addSql('DROP TABLE ecran');
        $this->addSql('DROP TABLE entreprise');
        $this->addSql('DROP TABLE lieux_ventes');
        $this->addSql('DROP TABLE mode_paiement');
        $this->addSql('DROP TABLE personnel');
        $this->addSql('DROP TABLE point_de_vente');
        $this->addSql('DROP TABLE region');
        $this->addSql('DROP TABLE service');
        $this->addSql('DROP TABLE `table`');
        $this->addSql('DROP INDEX IDX_8D93D649AA2B41DC ON user');
        $this->addSql('DROP INDEX IDX_8D93D64998260155 ON user');
        $this->addSql('ALTER TABLE user DROP lieu_vente_id, DROP region_id, DROP nom, DROP prenom, DROP telephone, DROP email, DROP type_user, DROP adresse, DROP ville, DROP pays, DROP reference, DROP date_creation, DROP statut');
    }
}
