<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240403143701 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commande_product (id INT AUTO_INCREMENT NOT NULL, facturation_id INT NOT NULL, product_id INT NOT NULL, prix_vente NUMERIC(13, 2) DEFAULT NULL, prix_revient NUMERIC(13, 2) DEFAULT NULL, prix_achat NUMERIC(13, 2) DEFAULT NULL, quantite DOUBLE PRECISION DEFAULT NULL, remise NUMERIC(10, 0) DEFAULT NULL, tva DOUBLE PRECISION DEFAULT NULL, INDEX IDX_25F1760DDBC5F284 (facturation_id), INDEX IDX_25F1760D4584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commande_product ADD CONSTRAINT FK_25F1760DDBC5F284 FOREIGN KEY (facturation_id) REFERENCES facturation (id)');
        $this->addSql('ALTER TABLE commande_product ADD CONSTRAINT FK_25F1760D4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande_product DROP FOREIGN KEY FK_25F1760DDBC5F284');
        $this->addSql('ALTER TABLE commande_product DROP FOREIGN KEY FK_25F1760D4584665A');
        $this->addSql('DROP TABLE commande_product');
    }
}
