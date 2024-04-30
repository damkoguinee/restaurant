<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240408072240 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cloture_caisse (id INT AUTO_INCREMENT NOT NULL, caisse_id INT NOT NULL, saisie_par_id INT NOT NULL, devise_id INT NOT NULL, journee DATE NOT NULL, montant_theo NUMERIC(13, 2) NOT NULL, montant_reel NUMERIC(13, 2) NOT NULL, date_saisie DATETIME NOT NULL, difference NUMERIC(13, 2) NOT NULL, INDEX IDX_21B102FD27B4FEBF (caisse_id), INDEX IDX_21B102FDC74AC7FE (saisie_par_id), INDEX IDX_21B102FDF4445056 (devise_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cloture_caisse ADD CONSTRAINT FK_21B102FD27B4FEBF FOREIGN KEY (caisse_id) REFERENCES caisse (id)');
        $this->addSql('ALTER TABLE cloture_caisse ADD CONSTRAINT FK_21B102FDC74AC7FE FOREIGN KEY (saisie_par_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE cloture_caisse ADD CONSTRAINT FK_21B102FDF4445056 FOREIGN KEY (devise_id) REFERENCES devise (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cloture_caisse DROP FOREIGN KEY FK_21B102FD27B4FEBF');
        $this->addSql('ALTER TABLE cloture_caisse DROP FOREIGN KEY FK_21B102FDC74AC7FE');
        $this->addSql('ALTER TABLE cloture_caisse DROP FOREIGN KEY FK_21B102FDF4445056');
        $this->addSql('DROP TABLE cloture_caisse');
    }
}
