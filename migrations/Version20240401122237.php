<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240401122237 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE liste_stock (id INT AUTO_INCREMENT NOT NULL, lieu_vente_id INT NOT NULL, responsable_id INT NOT NULL, nom_stock VARCHAR(100) NOT NULL, adresse VARCHAR(255) NOT NULL, surface DOUBLE PRECISION DEFAULT NULL, nbre_piece INT DEFAULT NULL, type VARCHAR(15) NOT NULL, INDEX IDX_54CD82CEAA2B41DC (lieu_vente_id), INDEX IDX_54CD82CE53C59D72 (responsable_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stock (id INT AUTO_INCREMENT NOT NULL, emplacement_id INT NOT NULL, quantite DOUBLE PRECISION DEFAULT NULL, date_maj DATETIME NOT NULL, prix_achat NUMERIC(13, 2) DEFAULT NULL, prix_revient NUMERIC(13, 2) DEFAULT NULL, prix_vente NUMERIC(13, 2) DEFAULT NULL, date_peremption DATE DEFAULT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_4B365660C4598A51 (emplacement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stock_plat (id INT AUTO_INCREMENT NOT NULL, plat_id INT NOT NULL, INDEX IDX_3922FAC3D73DB560 (plat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE liste_stock ADD CONSTRAINT FK_54CD82CEAA2B41DC FOREIGN KEY (lieu_vente_id) REFERENCES lieux_ventes (id)');
        $this->addSql('ALTER TABLE liste_stock ADD CONSTRAINT FK_54CD82CE53C59D72 FOREIGN KEY (responsable_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE stock ADD CONSTRAINT FK_4B365660C4598A51 FOREIGN KEY (emplacement_id) REFERENCES liste_stock (id)');
        $this->addSql('ALTER TABLE stock_plat ADD CONSTRAINT FK_3922FAC3D73DB560 FOREIGN KEY (plat_id) REFERENCES plat (id)');
        $this->addSql('ALTER TABLE stock_boisson DROP FOREIGN KEY FK_9231D8EEAA2B41DC');
        $this->addSql('DROP INDEX IDX_9231D8EEAA2B41DC ON stock_boisson');
        $this->addSql('ALTER TABLE stock_boisson DROP lieu_vente_id, DROP quantite, DROP date_maj, CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE stock_boisson ADD CONSTRAINT FK_9231D8EEBF396750 FOREIGN KEY (id) REFERENCES stock (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE stock_dessert DROP FOREIGN KEY FK_608F0B35AA2B41DC');
        $this->addSql('DROP INDEX IDX_608F0B35AA2B41DC ON stock_dessert');
        $this->addSql('ALTER TABLE stock_dessert DROP lieu_vente_id, DROP quantite, DROP date_maj, CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE stock_dessert ADD CONSTRAINT FK_608F0B35BF396750 FOREIGN KEY (id) REFERENCES stock (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE stock_ingredient DROP FOREIGN KEY FK_C5E68FDCAA2B41DC');
        $this->addSql('DROP INDEX IDX_C5E68FDCAA2B41DC ON stock_ingredient');
        $this->addSql('ALTER TABLE stock_ingredient DROP lieu_vente_id, DROP quantite, DROP date_maj, CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE stock_ingredient ADD CONSTRAINT FK_C5E68FDCBF396750 FOREIGN KEY (id) REFERENCES stock (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE stock_boisson DROP FOREIGN KEY FK_9231D8EEBF396750');
        $this->addSql('ALTER TABLE stock_dessert DROP FOREIGN KEY FK_608F0B35BF396750');
        $this->addSql('ALTER TABLE stock_ingredient DROP FOREIGN KEY FK_C5E68FDCBF396750');
        $this->addSql('ALTER TABLE liste_stock DROP FOREIGN KEY FK_54CD82CEAA2B41DC');
        $this->addSql('ALTER TABLE liste_stock DROP FOREIGN KEY FK_54CD82CE53C59D72');
        $this->addSql('ALTER TABLE stock DROP FOREIGN KEY FK_4B365660C4598A51');
        $this->addSql('ALTER TABLE stock_plat DROP FOREIGN KEY FK_3922FAC3D73DB560');
        $this->addSql('DROP TABLE liste_stock');
        $this->addSql('DROP TABLE stock');
        $this->addSql('DROP TABLE stock_plat');
        $this->addSql('ALTER TABLE stock_boisson ADD lieu_vente_id INT NOT NULL, ADD quantite DOUBLE PRECISION DEFAULT NULL, ADD date_maj DATETIME NOT NULL, CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE stock_boisson ADD CONSTRAINT FK_9231D8EEAA2B41DC FOREIGN KEY (lieu_vente_id) REFERENCES lieux_ventes (id)');
        $this->addSql('CREATE INDEX IDX_9231D8EEAA2B41DC ON stock_boisson (lieu_vente_id)');
        $this->addSql('ALTER TABLE stock_dessert ADD lieu_vente_id INT NOT NULL, ADD quantite INT NOT NULL, ADD date_maj DATETIME NOT NULL, CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE stock_dessert ADD CONSTRAINT FK_608F0B35AA2B41DC FOREIGN KEY (lieu_vente_id) REFERENCES lieux_ventes (id)');
        $this->addSql('CREATE INDEX IDX_608F0B35AA2B41DC ON stock_dessert (lieu_vente_id)');
        $this->addSql('ALTER TABLE stock_ingredient ADD lieu_vente_id INT NOT NULL, ADD quantite DOUBLE PRECISION DEFAULT NULL, ADD date_maj DATETIME NOT NULL, CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE stock_ingredient ADD CONSTRAINT FK_C5E68FDCAA2B41DC FOREIGN KEY (lieu_vente_id) REFERENCES lieux_ventes (id)');
        $this->addSql('CREATE INDEX IDX_C5E68FDCAA2B41DC ON stock_ingredient (lieu_vente_id)');
    }
}
