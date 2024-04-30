<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240327150426 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE table_commande ADD lieu_vente_id INT NOT NULL');
        $this->addSql('ALTER TABLE table_commande ADD CONSTRAINT FK_FE5788ADAA2B41DC FOREIGN KEY (lieu_vente_id) REFERENCES lieux_ventes (id)');
        $this->addSql('CREATE INDEX IDX_FE5788ADAA2B41DC ON table_commande (lieu_vente_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE table_commande DROP FOREIGN KEY FK_FE5788ADAA2B41DC');
        $this->addSql('DROP INDEX IDX_FE5788ADAA2B41DC ON table_commande');
        $this->addSql('ALTER TABLE table_commande DROP lieu_vente_id');
    }
}
