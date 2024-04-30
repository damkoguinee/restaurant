<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240325141208 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE journee_travail (id INT AUTO_INCREMENT NOT NULL, lieu_vente_id INT NOT NULL, saisie_par_id INT NOT NULL, cloturer_par_id INT DEFAULT NULL, jour_de_travail DATE NOT NULL, statut VARCHAR(10) NOT NULL, date_saisie DATETIME NOT NULL, date_cloture DATETIME DEFAULT NULL, INDEX IDX_E57A227FAA2B41DC (lieu_vente_id), INDEX IDX_E57A227FC74AC7FE (saisie_par_id), INDEX IDX_E57A227F8BE4C0DC (cloturer_par_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE journee_travail ADD CONSTRAINT FK_E57A227FAA2B41DC FOREIGN KEY (lieu_vente_id) REFERENCES lieux_ventes (id)');
        $this->addSql('ALTER TABLE journee_travail ADD CONSTRAINT FK_E57A227FC74AC7FE FOREIGN KEY (saisie_par_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE journee_travail ADD CONSTRAINT FK_E57A227F8BE4C0DC FOREIGN KEY (cloturer_par_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE journee_travail DROP FOREIGN KEY FK_E57A227FAA2B41DC');
        $this->addSql('ALTER TABLE journee_travail DROP FOREIGN KEY FK_E57A227FC74AC7FE');
        $this->addSql('ALTER TABLE journee_travail DROP FOREIGN KEY FK_E57A227F8BE4C0DC');
        $this->addSql('DROP TABLE journee_travail');
    }
}
