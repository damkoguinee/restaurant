<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240405141540 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE modification_commande DROP FOREIGN KEY FK_53A62731C74AC7FE');
        $this->addSql('DROP INDEX IDX_53A62731C74AC7FE ON modification_commande');
        $this->addSql('ALTER TABLE modification_commande ADD emplacement_id INT NOT NULL, CHANGE saisie_par_id product_id INT NOT NULL');
        $this->addSql('ALTER TABLE modification_commande ADD CONSTRAINT FK_53A627314584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE modification_commande ADD CONSTRAINT FK_53A62731C4598A51 FOREIGN KEY (emplacement_id) REFERENCES `table` (id)');
        $this->addSql('CREATE INDEX IDX_53A627314584665A ON modification_commande (product_id)');
        $this->addSql('CREATE INDEX IDX_53A62731C4598A51 ON modification_commande (emplacement_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE modification_commande DROP FOREIGN KEY FK_53A627314584665A');
        $this->addSql('ALTER TABLE modification_commande DROP FOREIGN KEY FK_53A62731C4598A51');
        $this->addSql('DROP INDEX IDX_53A627314584665A ON modification_commande');
        $this->addSql('DROP INDEX IDX_53A62731C4598A51 ON modification_commande');
        $this->addSql('ALTER TABLE modification_commande ADD saisie_par_id INT NOT NULL, DROP product_id, DROP emplacement_id');
        $this->addSql('ALTER TABLE modification_commande ADD CONSTRAINT FK_53A62731C74AC7FE FOREIGN KEY (saisie_par_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_53A62731C74AC7FE ON modification_commande (saisie_par_id)');
    }
}
