<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240325121115 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chicha ADD lieu_vente_id INT NOT NULL, ADD etat VARCHAR(6) NOT NULL');
        $this->addSql('ALTER TABLE chicha ADD CONSTRAINT FK_DCE9D4AAA2B41DC FOREIGN KEY (lieu_vente_id) REFERENCES lieux_ventes (id)');
        $this->addSql('CREATE INDEX IDX_DCE9D4AAA2B41DC ON chicha (lieu_vente_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chicha DROP FOREIGN KEY FK_DCE9D4AAA2B41DC');
        $this->addSql('DROP INDEX IDX_DCE9D4AAA2B41DC ON chicha');
        $this->addSql('ALTER TABLE chicha DROP lieu_vente_id, DROP etat');
    }
}
