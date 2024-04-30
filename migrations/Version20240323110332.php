<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240323110332 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, saisie_par_id INT NOT NULL, table_client_id INT DEFAULT NULL, service_id INT NOT NULL, montant_commande NUMERIC(13, 2) DEFAULT NULL, montant_paye NUMERIC(13, 2) DEFAULT NULL, date_commande DATETIME NOT NULL, date_saisie DATETIME NOT NULL, INDEX IDX_6EEAA67D19EB6921 (client_id), INDEX IDX_6EEAA67DC74AC7FE (saisie_par_id), INDEX IDX_6EEAA67D9FA806DB (table_client_id), INDEX IDX_6EEAA67DED5CA9E6 (service_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commande_detail (id INT AUTO_INCREMENT NOT NULL, commande_id INT NOT NULL, INDEX IDX_2C52844682EA2E54 (commande_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D19EB6921 FOREIGN KEY (client_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DC74AC7FE FOREIGN KEY (saisie_par_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D9FA806DB FOREIGN KEY (table_client_id) REFERENCES `table` (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
        $this->addSql('ALTER TABLE commande_detail ADD CONSTRAINT FK_2C52844682EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D19EB6921');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DC74AC7FE');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D9FA806DB');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DED5CA9E6');
        $this->addSql('ALTER TABLE commande_detail DROP FOREIGN KEY FK_2C52844682EA2E54');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE commande_detail');
    }
}
