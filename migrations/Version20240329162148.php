<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240329162148 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE boisson (id INT NOT NULL, volume DOUBLE PRECISION DEFAULT NULL, unite VARCHAR(5) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chicha (id INT NOT NULL, etat VARCHAR(6) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cocktail (id INT NOT NULL, etat VARCHAR(6) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE plat (id INT NOT NULL, etat VARCHAR(6) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, service_id INT NOT NULL, lieu_vente_id INT DEFAULT NULL, nom VARCHAR(100) NOT NULL, description VARCHAR(255) DEFAULT NULL, prix_vente NUMERIC(13, 2) NOT NULL, image VARCHAR(255) DEFAULT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_D34A04ADED5CA9E6 (service_id), INDEX IDX_D34A04ADAA2B41DC (lieu_vente_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE boisson ADD CONSTRAINT FK_8B97C84DBF396750 FOREIGN KEY (id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE chicha ADD CONSTRAINT FK_DCE9D4ABF396750 FOREIGN KEY (id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cocktail ADD CONSTRAINT FK_7B4914D4BF396750 FOREIGN KEY (id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE plat ADD CONSTRAINT FK_2038A207BF396750 FOREIGN KEY (id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADAA2B41DC FOREIGN KEY (lieu_vente_id) REFERENCES lieux_ventes (id)');
        $this->addSql('DROP TABLE test_interface');
        $this->addSql('ALTER TABLE table_commande DROP FOREIGN KEY FK_FE5788ADCD6F76C6');
        $this->addSql('DROP INDEX IDX_FE5788ADCD6F76C6 ON table_commande');
        $this->addSql('ALTER TABLE table_commande DROP cocktail_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cocktail_recette DROP FOREIGN KEY FK_8A60C8AC734B8089');
        $this->addSql('ALTER TABLE stock_boisson DROP FOREIGN KEY FK_9231D8EE734B8089');
        $this->addSql('ALTER TABLE chicha_recette DROP FOREIGN KEY FK_D8C3F041A37FD6CB');
        $this->addSql('ALTER TABLE cocktail_recette DROP FOREIGN KEY FK_8A60C8ACCD6F76C6');
        $this->addSql('ALTER TABLE plat_recette DROP FOREIGN KEY FK_CEEAE377D73DB560');
        $this->addSql('CREATE TABLE test_interface (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE boisson DROP FOREIGN KEY FK_8B97C84DBF396750');
        $this->addSql('ALTER TABLE chicha DROP FOREIGN KEY FK_DCE9D4ABF396750');
        $this->addSql('ALTER TABLE cocktail DROP FOREIGN KEY FK_7B4914D4BF396750');
        $this->addSql('ALTER TABLE plat DROP FOREIGN KEY FK_2038A207BF396750');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADED5CA9E6');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADAA2B41DC');
        $this->addSql('DROP TABLE boisson');
        $this->addSql('DROP TABLE chicha');
        $this->addSql('DROP TABLE cocktail');
        $this->addSql('DROP TABLE plat');
        $this->addSql('DROP TABLE product');
        $this->addSql('ALTER TABLE table_commande ADD cocktail_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE table_commande ADD CONSTRAINT FK_FE5788ADCD6F76C6 FOREIGN KEY (cocktail_id) REFERENCES cocktail (id)');
        $this->addSql('CREATE INDEX IDX_FE5788ADCD6F76C6 ON table_commande (cocktail_id)');
    }
}
