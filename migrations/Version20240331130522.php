<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240331130522 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE table_commande ADD prepare_par_id INT DEFAULT NULL, ADD servie_par_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE table_commande ADD CONSTRAINT FK_FE5788ADF50FDE33 FOREIGN KEY (prepare_par_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE table_commande ADD CONSTRAINT FK_FE5788ADAFB2744A FOREIGN KEY (servie_par_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_FE5788ADF50FDE33 ON table_commande (prepare_par_id)');
        $this->addSql('CREATE INDEX IDX_FE5788ADAFB2744A ON table_commande (servie_par_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE table_commande DROP FOREIGN KEY FK_FE5788ADF50FDE33');
        $this->addSql('ALTER TABLE table_commande DROP FOREIGN KEY FK_FE5788ADAFB2744A');
        $this->addSql('DROP INDEX IDX_FE5788ADF50FDE33 ON table_commande');
        $this->addSql('DROP INDEX IDX_FE5788ADAFB2744A ON table_commande');
        $this->addSql('ALTER TABLE table_commande DROP prepare_par_id, DROP servie_par_id');
    }
}
