<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240327143939 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE table_commande (id INT AUTO_INCREMENT NOT NULL, table_commande_id INT DEFAULT NULL, cocktail_id INT DEFAULT NULL, saisie_par_id INT NOT NULL, quantite DOUBLE PRECISION NOT NULL, prix_vente NUMERIC(13, 2) DEFAULT NULL, date_commande DATETIME NOT NULL, date_saisie DATETIME NOT NULL, INDEX IDX_FE5788ADB1B4DDE9 (table_commande_id), INDEX IDX_FE5788ADCD6F76C6 (cocktail_id), INDEX IDX_FE5788ADC74AC7FE (saisie_par_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE table_commande ADD CONSTRAINT FK_FE5788ADB1B4DDE9 FOREIGN KEY (table_commande_id) REFERENCES table_commande (id)');
        $this->addSql('ALTER TABLE table_commande ADD CONSTRAINT FK_FE5788ADCD6F76C6 FOREIGN KEY (cocktail_id) REFERENCES cocktail (id)');
        $this->addSql('ALTER TABLE table_commande ADD CONSTRAINT FK_FE5788ADC74AC7FE FOREIGN KEY (saisie_par_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE mode_paiement CHANGE designation designation VARCHAR(50) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE table_commande DROP FOREIGN KEY FK_FE5788ADB1B4DDE9');
        $this->addSql('ALTER TABLE table_commande DROP FOREIGN KEY FK_FE5788ADCD6F76C6');
        $this->addSql('ALTER TABLE table_commande DROP FOREIGN KEY FK_FE5788ADC74AC7FE');
        $this->addSql('DROP TABLE table_commande');
        $this->addSql('ALTER TABLE mode_paiement CHANGE designation designation VARCHAR(100) NOT NULL');
    }
}
