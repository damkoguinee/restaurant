<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240327180653 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE table_commande DROP FOREIGN KEY FK_FE5788ADB1B4DDE9');
        $this->addSql('DROP INDEX IDX_FE5788ADB1B4DDE9 ON table_commande');
        $this->addSql('ALTER TABLE table_commande CHANGE table_commande_id emplacement_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE table_commande ADD CONSTRAINT FK_FE5788ADC4598A51 FOREIGN KEY (emplacement_id) REFERENCES `table` (id)');
        $this->addSql('CREATE INDEX IDX_FE5788ADC4598A51 ON table_commande (emplacement_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE table_commande DROP FOREIGN KEY FK_FE5788ADC4598A51');
        $this->addSql('DROP INDEX IDX_FE5788ADC4598A51 ON table_commande');
        $this->addSql('ALTER TABLE table_commande CHANGE emplacement_id table_commande_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE table_commande ADD CONSTRAINT FK_FE5788ADB1B4DDE9 FOREIGN KEY (table_commande_id) REFERENCES table_commande (id)');
        $this->addSql('CREATE INDEX IDX_FE5788ADB1B4DDE9 ON table_commande (table_commande_id)');
    }
}
