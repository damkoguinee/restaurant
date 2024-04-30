<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240322102250 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE boisson (id INT AUTO_INCREMENT NOT NULL, service_id INT NOT NULL, nom VARCHAR(100) NOT NULL, description VARCHAR(255) DEFAULT NULL, prix_vente NUMERIC(13, 2) DEFAULT NULL, volume DOUBLE PRECISION DEFAULT NULL, unite VARCHAR(5) DEFAULT NULL, INDEX IDX_8B97C84DED5CA9E6 (service_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chicha (id INT AUTO_INCREMENT NOT NULL, service_id INT NOT NULL, nom VARCHAR(100) NOT NULL, description VARCHAR(255) DEFAULT NULL, prix_vente NUMERIC(13, 2) NOT NULL, INDEX IDX_DCE9D4AED5CA9E6 (service_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chicha_recette (id INT AUTO_INCREMENT NOT NULL, chicha_id INT NOT NULL, ingredient_id INT NOT NULL, quantite DOUBLE PRECISION DEFAULT NULL, INDEX IDX_D8C3F041A37FD6CB (chicha_id), INDEX IDX_D8C3F041933FE08C (ingredient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cocktail (id INT AUTO_INCREMENT NOT NULL, service_id INT NOT NULL, nom VARCHAR(100) NOT NULL, description VARCHAR(255) DEFAULT NULL, prix_vente NUMERIC(13, 2) NOT NULL, INDEX IDX_7B4914D4ED5CA9E6 (service_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cocktail_recette (id INT AUTO_INCREMENT NOT NULL, cocktail_id INT NOT NULL, ingredient_id INT DEFAULT NULL, quantite DOUBLE PRECISION DEFAULT NULL, INDEX IDX_8A60C8ACCD6F76C6 (cocktail_id), INDEX IDX_8A60C8AC933FE08C (ingredient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE decaissement (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, lieu_vente_id INT NOT NULL, traite_par_id INT NOT NULL, devise_id INT NOT NULL, mode_paie_id INT NOT NULL, compte_decaisser_id INT NOT NULL, categorie_id INT NOT NULL, reference VARCHAR(255) NOT NULL, montant NUMERIC(13, 2) NOT NULL, numero_cheque_bord VARCHAR(100) DEFAULT NULL, banque_cheque VARCHAR(100) DEFAULT NULL, commentaire VARCHAR(255) DEFAULT NULL, date_decaissement DATETIME NOT NULL, date_saisie DATETIME NOT NULL, document VARCHAR(255) DEFAULT NULL, INDEX IDX_E5CCA29B19EB6921 (client_id), INDEX IDX_E5CCA29BAA2B41DC (lieu_vente_id), INDEX IDX_E5CCA29B167FABE8 (traite_par_id), INDEX IDX_E5CCA29BF4445056 (devise_id), INDEX IDX_E5CCA29B62E04A07 (mode_paie_id), INDEX IDX_E5CCA29BFA9687D0 (compte_decaisser_id), INDEX IDX_E5CCA29BBCF5E72D (categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dessert (id INT AUTO_INCREMENT NOT NULL, service_id INT NOT NULL, nom VARCHAR(100) NOT NULL, description VARCHAR(255) DEFAULT NULL, prix_vente NUMERIC(13, 2) NOT NULL, INDEX IDX_79291B96ED5CA9E6 (service_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE entree (id INT AUTO_INCREMENT NOT NULL, service_id INT NOT NULL, nom VARCHAR(100) NOT NULL, description VARCHAR(255) DEFAULT NULL, prix_vente DOUBLE PRECISION DEFAULT NULL, INDEX IDX_598377A6ED5CA9E6 (service_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ingredient (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, description VARCHAR(255) DEFAULT NULL, unite_mesure VARCHAR(10) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE plat (id INT AUTO_INCREMENT NOT NULL, service_id INT NOT NULL, nom VARCHAR(100) NOT NULL, description VARCHAR(255) DEFAULT NULL, prix_vente DOUBLE PRECISION NOT NULL, INDEX IDX_2038A207ED5CA9E6 (service_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE plat_recette (id INT AUTO_INCREMENT NOT NULL, plat_id INT NOT NULL, ingredient_id INT DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, INDEX IDX_CEEAE377D73DB560 (plat_id), INDEX IDX_CEEAE377933FE08C (ingredient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stock_boisson (id INT AUTO_INCREMENT NOT NULL, boisson_id INT NOT NULL, lieu_vente_id INT NOT NULL, quantite DOUBLE PRECISION DEFAULT NULL, date_maj DATETIME NOT NULL, INDEX IDX_9231D8EE734B8089 (boisson_id), INDEX IDX_9231D8EEAA2B41DC (lieu_vente_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stock_dessert (id INT AUTO_INCREMENT NOT NULL, dessert_id INT NOT NULL, lieu_vente_id INT NOT NULL, quantite INT NOT NULL, date_maj DATETIME NOT NULL, INDEX IDX_608F0B35745B52FD (dessert_id), INDEX IDX_608F0B35AA2B41DC (lieu_vente_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stock_ingredient (id INT AUTO_INCREMENT NOT NULL, ingredient_id INT NOT NULL, lieu_vente_id INT NOT NULL, quantite DOUBLE PRECISION DEFAULT NULL, date_maj DATETIME NOT NULL, INDEX IDX_C5E68FDC933FE08C (ingredient_id), INDEX IDX_C5E68FDCAA2B41DC (lieu_vente_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE boisson ADD CONSTRAINT FK_8B97C84DED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
        $this->addSql('ALTER TABLE chicha ADD CONSTRAINT FK_DCE9D4AED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
        $this->addSql('ALTER TABLE chicha_recette ADD CONSTRAINT FK_D8C3F041A37FD6CB FOREIGN KEY (chicha_id) REFERENCES chicha (id)');
        $this->addSql('ALTER TABLE chicha_recette ADD CONSTRAINT FK_D8C3F041933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id)');
        $this->addSql('ALTER TABLE cocktail ADD CONSTRAINT FK_7B4914D4ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
        $this->addSql('ALTER TABLE cocktail_recette ADD CONSTRAINT FK_8A60C8ACCD6F76C6 FOREIGN KEY (cocktail_id) REFERENCES cocktail (id)');
        $this->addSql('ALTER TABLE cocktail_recette ADD CONSTRAINT FK_8A60C8AC933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id)');
        $this->addSql('ALTER TABLE decaissement ADD CONSTRAINT FK_E5CCA29B19EB6921 FOREIGN KEY (client_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE decaissement ADD CONSTRAINT FK_E5CCA29BAA2B41DC FOREIGN KEY (lieu_vente_id) REFERENCES lieux_ventes (id)');
        $this->addSql('ALTER TABLE decaissement ADD CONSTRAINT FK_E5CCA29B167FABE8 FOREIGN KEY (traite_par_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE decaissement ADD CONSTRAINT FK_E5CCA29BF4445056 FOREIGN KEY (devise_id) REFERENCES devise (id)');
        $this->addSql('ALTER TABLE decaissement ADD CONSTRAINT FK_E5CCA29B62E04A07 FOREIGN KEY (mode_paie_id) REFERENCES mode_paiement (id)');
        $this->addSql('ALTER TABLE decaissement ADD CONSTRAINT FK_E5CCA29BFA9687D0 FOREIGN KEY (compte_decaisser_id) REFERENCES caisse (id)');
        $this->addSql('ALTER TABLE decaissement ADD CONSTRAINT FK_E5CCA29BBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie_decaissement (id)');
        $this->addSql('ALTER TABLE dessert ADD CONSTRAINT FK_79291B96ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
        $this->addSql('ALTER TABLE entree ADD CONSTRAINT FK_598377A6ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
        $this->addSql('ALTER TABLE plat ADD CONSTRAINT FK_2038A207ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
        $this->addSql('ALTER TABLE plat_recette ADD CONSTRAINT FK_CEEAE377D73DB560 FOREIGN KEY (plat_id) REFERENCES plat (id)');
        $this->addSql('ALTER TABLE plat_recette ADD CONSTRAINT FK_CEEAE377933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id)');
        $this->addSql('ALTER TABLE stock_boisson ADD CONSTRAINT FK_9231D8EE734B8089 FOREIGN KEY (boisson_id) REFERENCES boisson (id)');
        $this->addSql('ALTER TABLE stock_boisson ADD CONSTRAINT FK_9231D8EEAA2B41DC FOREIGN KEY (lieu_vente_id) REFERENCES lieux_ventes (id)');
        $this->addSql('ALTER TABLE stock_dessert ADD CONSTRAINT FK_608F0B35745B52FD FOREIGN KEY (dessert_id) REFERENCES dessert (id)');
        $this->addSql('ALTER TABLE stock_dessert ADD CONSTRAINT FK_608F0B35AA2B41DC FOREIGN KEY (lieu_vente_id) REFERENCES lieux_ventes (id)');
        $this->addSql('ALTER TABLE stock_ingredient ADD CONSTRAINT FK_C5E68FDC933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id)');
        $this->addSql('ALTER TABLE stock_ingredient ADD CONSTRAINT FK_C5E68FDCAA2B41DC FOREIGN KEY (lieu_vente_id) REFERENCES lieux_ventes (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE boisson DROP FOREIGN KEY FK_8B97C84DED5CA9E6');
        $this->addSql('ALTER TABLE chicha DROP FOREIGN KEY FK_DCE9D4AED5CA9E6');
        $this->addSql('ALTER TABLE chicha_recette DROP FOREIGN KEY FK_D8C3F041A37FD6CB');
        $this->addSql('ALTER TABLE chicha_recette DROP FOREIGN KEY FK_D8C3F041933FE08C');
        $this->addSql('ALTER TABLE cocktail DROP FOREIGN KEY FK_7B4914D4ED5CA9E6');
        $this->addSql('ALTER TABLE cocktail_recette DROP FOREIGN KEY FK_8A60C8ACCD6F76C6');
        $this->addSql('ALTER TABLE cocktail_recette DROP FOREIGN KEY FK_8A60C8AC933FE08C');
        $this->addSql('ALTER TABLE decaissement DROP FOREIGN KEY FK_E5CCA29B19EB6921');
        $this->addSql('ALTER TABLE decaissement DROP FOREIGN KEY FK_E5CCA29BAA2B41DC');
        $this->addSql('ALTER TABLE decaissement DROP FOREIGN KEY FK_E5CCA29B167FABE8');
        $this->addSql('ALTER TABLE decaissement DROP FOREIGN KEY FK_E5CCA29BF4445056');
        $this->addSql('ALTER TABLE decaissement DROP FOREIGN KEY FK_E5CCA29B62E04A07');
        $this->addSql('ALTER TABLE decaissement DROP FOREIGN KEY FK_E5CCA29BFA9687D0');
        $this->addSql('ALTER TABLE decaissement DROP FOREIGN KEY FK_E5CCA29BBCF5E72D');
        $this->addSql('ALTER TABLE dessert DROP FOREIGN KEY FK_79291B96ED5CA9E6');
        $this->addSql('ALTER TABLE entree DROP FOREIGN KEY FK_598377A6ED5CA9E6');
        $this->addSql('ALTER TABLE plat DROP FOREIGN KEY FK_2038A207ED5CA9E6');
        $this->addSql('ALTER TABLE plat_recette DROP FOREIGN KEY FK_CEEAE377D73DB560');
        $this->addSql('ALTER TABLE plat_recette DROP FOREIGN KEY FK_CEEAE377933FE08C');
        $this->addSql('ALTER TABLE stock_boisson DROP FOREIGN KEY FK_9231D8EE734B8089');
        $this->addSql('ALTER TABLE stock_boisson DROP FOREIGN KEY FK_9231D8EEAA2B41DC');
        $this->addSql('ALTER TABLE stock_dessert DROP FOREIGN KEY FK_608F0B35745B52FD');
        $this->addSql('ALTER TABLE stock_dessert DROP FOREIGN KEY FK_608F0B35AA2B41DC');
        $this->addSql('ALTER TABLE stock_ingredient DROP FOREIGN KEY FK_C5E68FDC933FE08C');
        $this->addSql('ALTER TABLE stock_ingredient DROP FOREIGN KEY FK_C5E68FDCAA2B41DC');
        $this->addSql('DROP TABLE boisson');
        $this->addSql('DROP TABLE chicha');
        $this->addSql('DROP TABLE chicha_recette');
        $this->addSql('DROP TABLE cocktail');
        $this->addSql('DROP TABLE cocktail_recette');
        $this->addSql('DROP TABLE decaissement');
        $this->addSql('DROP TABLE dessert');
        $this->addSql('DROP TABLE entree');
        $this->addSql('DROP TABLE ingredient');
        $this->addSql('DROP TABLE plat');
        $this->addSql('DROP TABLE plat_recette');
        $this->addSql('DROP TABLE stock_boisson');
        $this->addSql('DROP TABLE stock_dessert');
        $this->addSql('DROP TABLE stock_ingredient');
    }
}
