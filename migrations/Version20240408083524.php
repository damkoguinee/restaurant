<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240408083524 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cloture_caisse CHANGE lieu_vente_id lieu_vente_id INT NOT NULL');
        $this->addSql('ALTER TABLE cloture_caisse ADD CONSTRAINT FK_21B102FDAA2B41DC FOREIGN KEY (lieu_vente_id) REFERENCES lieux_ventes (id)');
        $this->addSql('CREATE INDEX IDX_21B102FDAA2B41DC ON cloture_caisse (lieu_vente_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cloture_caisse DROP FOREIGN KEY FK_21B102FDAA2B41DC');
        $this->addSql('DROP INDEX IDX_21B102FDAA2B41DC ON cloture_caisse');
        $this->addSql('ALTER TABLE cloture_caisse CHANGE lieu_vente_id lieu_vente_id INT DEFAULT 1 NOT NULL');
    }
}
